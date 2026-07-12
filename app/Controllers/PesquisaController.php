<?php

namespace App\Controllers;

use App\Models\PesquisaModel;
use App\Models\EstabelecimentoModel;
use CodeIgniter\RESTful\ResourceController;

class PesquisaController extends ResourceController
{
    protected $modelName = 'App\Models\PesquisaModel';
    protected $format = 'json';

    /**
     * Renderiza o formulário de pesquisa carregando as preferências do local
     */
    public function index()
    {
        $token = $this->request->getGet('token') ?? $this->request->getGet('id');
        $estabelecimento = null;

        if ($token) {
            $estabelecimentoModel = new EstabelecimentoModel();
            $estabelecimento = $estabelecimentoModel->where('token_qr_code', $token)
                ->orWhere('id_estabelecimento', $token)
                ->first();
        }

        return view('pesquisa', [
            'estabelecimento' => $estabelecimento
        ]);
    }

    /**
     * Mapeia o guia com destaque privilegiado baseado em SEO para lojistas da rede
     */
    public function guia()
    {
        $db = \Config\Database::connect();

        // Ordenamos os estabelecimentos ativos priorizando quem participa da rede de vantagens (Selo de Destaque)
        $estabelecimentos = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->orderBy('ee.aceita_desconto', 'DESC')
            ->orderBy('ee.razao_social', 'ASC')
            ->get()
            ->getResultArray();

        return view('guia', ['estabelecimentos' => $estabelecimentos]);
    }

    public function sucesso()
    {
        return view('sucesso');
    }

    /**
     * MOTOR DE SUBMISSÃO DA PESQUISA COM FILTRO DE MORADOR E TRAVAS DE SPAM CORRIGIDO
     */
    public function salvar()
    {
        try {
            $dados = $this->request->getJSON(true);

            if (empty($dados)) {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => ['error' => 'Nenhum dado enviado.']
                ], 400);
            }

            $db = \Config\Database::connect();

            // 1. CONVERSÃO DE TOKEN PARA ID NUMÉRICO DO BANCO
            if (isset($dados['id_estabelecimento'])) {
                $local = $db->table('estabelecimento_evento')
                    ->where('token_qr_code', $dados['id_estabelecimento'])
                    ->orWhere('id_estabelecimento', $dados['id_estabelecimento'])
                    ->get()
                    ->getRowArray();

                if ($local) {
                    $idEstabelecimento = $local['id_estabelecimento'];
                    $dados['id_estabelecimento'] = $idEstabelecimento;
                } else {
                    return $this->respond([
                        'status' => 400,
                        'success' => false,
                        'messages' => ['error' => 'Estabelecimento inválido.']
                    ], 400);
                }
            } else {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => ['error' => 'ID do estabelecimento é obrigatório.']
                ], 400);
            }

            // 2. GARANTIA DE UNICIDADE COM TRATAMENTO DE VALORES NULOS/VAZIOS (PREVENT COALITION)
            $cpfLimpo = preg_replace('/\D/', '', $dados['cpf'] ?? '');
            $deviceHash = isset($dados['device_hash']) ? trim($dados['device_hash']) : '';

            if (empty($cpfLimpo)) {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => ['error' => 'CPF é obrigatório para validação de segurança.']
                ], 400);
            }

            // Iniciamos a query de duplicidade limitando-se ao estabelecimento alvo e à janela de 30 dias
            $duplicidadeQuery = $db->table('pesquisa')
                ->where('id_estabelecimento', $idEstabelecimento)
                ->where('respondido_em >=', date('Y-m-d H:i:s', strtotime('-30 days')));

            // Agrupamento lógico preventivo: CPF coincide OU Device coincidente (Apenas se o Device for válido)
            $duplicidadeQuery->groupStart();
            $duplicidadeQuery->where('cpf', $cpfLimpo);

            // Só valida duplicidade por Device se ele não for vazio, nulo ou genérico demais
            if (!empty($deviceHash) && strlen($deviceHash) > 10) {
                $duplicidadeQuery->orGroupStart()
                    ->where('device_hash', $deviceHash)
                    ->where('device_hash IS NOT NULL')
                    ->where('device_hash !=', '')
                    ->groupEnd();
            }
            $duplicidadeQuery->groupEnd();

            $duplicidade = $duplicidadeQuery->get()->getRowArray();

            if ($duplicidade) {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => [
                        'error' => 'Acesso limitado: Você já enviou uma avaliação para este estabelecimento nos últimos 30 dias.'
                    ]
                ], 400);
            }

            // 3. TRIAGEM DO MORADOR MUNICIPAL
            $isMorador = $db->table('morador_novalima')
                ->where('cpf', $cpfLimpo)
                ->countAllResults() > 0;

            // Ajuste de tipagem fiscal
            if (isset($dados['faixa_gasto'])) {
                $dados['valor_gasto_estimado'] = (float) $dados['faixa_gasto'];
                unset($dados['faixa_gasto']);
            }

            if (isset($dados['tempo_permanencia']) && $dados['tempo_permanencia'] === 'bate_volta') {
                $dados['local_hospedagem'] = null;
            }

            $payloadPesquisa = [
                'id_estabelecimento' => $dados['id_estabelecimento'],
                'cidade_origem' => $dados['cidade_origem'],
                'tempo_permanencia' => $dados['tempo_permanencia'],
                'local_hospedagem' => $dados['local_hospedagem'],
                'valor_gasto_estimado' => $dados['valor_gasto_estimado'] ?? 0.00,
                'satisfacao_estrelas' => (int) $dados['satisfacao_estrelas'],
                'nps' => (int) $dados['nps'],
                'motivo_visita' => $dados['motivo_visita'],
                'cpf' => $cpfLimpo,
                'device_hash' => (!empty($deviceHash) && strlen($deviceHash) > 10) ? $deviceHash : null,
                'is_morador' => $isMorador ? 1 : 0
            ];

            if ($db->table('pesquisa')->insert($payloadPesquisa)) {
                return $this->respond([
                    'status' => 201,
                    'success' => true,
                    'is_resident' => $isMorador,
                    'origem' => $dados['origem'] ?? 'guia',
                    'message' => 'Pesquisa avaliativa gravada com sucesso.'
                ], 201);
            }

            return $this->respond([
                'status' => 400,
                'success' => false,
                'messages' => ['error' => 'Falha interna ao processar a gravação no banco.']
            ], 400);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => 500,
                'success' => false,
                'messages' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * API: VALIDAÇÃO DO PIN DE BALCÃO PARA ATIVAÇÃO DO CRONÔMETRO
     */
    public function validarPin()
    {
        try {
            $dados = $this->request->getJSON(true);

            $token = $dados['token'] ?? '';
            $pinDigitado = $dados['pin'] ?? '';

            if (empty($token) || empty($pinDigitado)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Parâmetros ausentes.'
                ], 400);
            }

            $db = \Config\Database::connect();
            $estabelecimento = $db->table('estabelecimento_evento')
                ->where('token_qr_code', $token)
                ->orWhere('id_estabelecimento', $token)
                ->get()
                ->getRowArray();

            if ($estabelecimento && $estabelecimento['pin_validacao'] === $pinDigitado) {
                return $this->respond([
                    'success' => true,
                    'message' => 'PIN de Balcão homologado com sucesso!'
                ]);
            }

            return $this->respond([
                'success' => false,
                'message' => 'PIN de validação inválido.'
            ], 401);

        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Erro interno ao validar PIN.'
            ], 500);
        }
    }
}