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
     * GET /pesquisa ou GET /turismo/visitar/(:any)
     * Renderiza a página do formulário e trata o roteamento do QR Code físico
     */
    public function index()
    {
        $token = $this->request->getGet('token') ?? $this->request->getGet('id');
        $estabelecimentoModel = new EstabelecimentoModel();

        // Busca o estabelecimento pelo QR code correspondente
        $estabelecimento = $estabelecimentoModel->where('token_qr_code', $token)->first();

        // Se não encontrar, retorna um erro amigável sem quebrar o framework
        if (!$estabelecimento) {
            return "Erro: O QR Code escaneado não aponta para nenhum estabelecimento cadastrado.";
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

        // Puxamos diretamente via Query Builder para garantir que não haja cache no Model
        $estabelecimentos = $db->table('estabelecimento_evento ee')
            ->select('ee.*, u.role_usuario')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->get()
            ->getResultArray();

        return view('guia', [
            'estabelecimentos' => $estabelecimentos
        ]);
    }

    public function sucesso()
    {
        return view('sucesso');
    }

    /**
     * MOTOR DE SUBMISSÃO DA PESQUISA COM FILTRO DE MORADOR E TRAVAS DE SPAM
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

            // 2. GARANTIA DE UNICIDADE DO ENVIO (CPF + DEVICE FINGERPRINT NOS ÚLTIMOS 30 DIAS)
            $cpfLimpo = preg_replace('/\D/', '', $dados['cpf'] ?? '');
            $deviceHash = $dados['device_hash'] ?? '';

            if (empty($cpfLimpo)) {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => ['error' => 'CPF é obrigatório para validação de segurança.']
                ], 400);
            }

            // Consulta duplicidade para este local nos últimos 30 dias de forma unificada
            $duplicidade = $db->table('pesquisa')
                ->where('id_estabelecimento', $idEstabelecimento)
                ->groupStart()
                ->where('cpf', $cpfLimpo)
                ->orWhere('device_hash', $deviceHash)
                ->groupEnd()
                ->where('respondido_em >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->get()
                ->getRowArray();

            if ($duplicidade) {
                return $this->respond([
                    'status' => 400,
                    'success' => false,
                    'messages' => [
                        'error' => 'Acesso limitado: Você já enviou uma avaliação para este estabelecimento nos últimos 30 dias.'
                    ]
                ], 400);
            }

            // 3. TRIAGEM DO MORADOR MUNICIPAL (Sem Placebo / Barramento Direto)
            $isMorador = $db->table('morador_novalima')
                ->where('cpf', $cpfLimpo)
                ->countAllResults() > 0;

            // Tratamento das colunas fiscais conforme validação do Model
            if (isset($dados['faixa_gasto'])) {
                $dados['valor_gasto_estimado'] = (float) $dados['faixa_gasto'];
                unset($dados['faixa_gasto']);
            }

            if (isset($dados['tempo_permanencia']) && $dados['tempo_permanencia'] === 'bate_volta') {
                $dados['local_hospedagem'] = null;
            }

            // CORREÇÃO: Se não receber origem explícita do front-end, o fallback definitivo é sempre 'qrcode' (Físico)
            $origemValida = (isset($dados['origem']) && $dados['origem'] === 'guia') ? 'guia' : 'qrcode';

            // Injeta dados de conformidade e segurança na tabela de pesquisas
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
                'device_hash' => $deviceHash,
                'is_morador' => $isMorador ? 1 : 0
            ];

            // Executamos a inserção de forma robusta e direta
            if ($db->table('pesquisa')->insert($payloadPesquisa)) {
                return $this->respond([
                    'status' => 201,
                    'success' => true,
                    'is_resident' => $isMorador,
                    'origem' => $origemValida, // Devolve sempre 'qrcode' ou 'guia' com segurança
                    'message' => 'Pesquisa avaliativa gravada com sucesso.'
                ], 201);
            }

            return $this->respond([
                'status' => 400,
                'success' => false,
                'messages' => $this->model->errors()
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
        $token = $this->request->getJSON(true)['token'] ?? null;
        $pin = $this->request->getJSON(true)['pin'] ?? null;

        if (!$token || !$pin) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dados insuficientes.']);
        }

        $estabelecimentoModel = new EstabelecimentoModel();
        $est = $estabelecimentoModel->where('token_qr_code', $token)->first();

        if ($est && $est['pin_validacao'] === $pin) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'PIN incorreto.']);
    }
}