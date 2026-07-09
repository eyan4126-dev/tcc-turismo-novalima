<?php

namespace App\Controllers;

use App\Models\PesquisaModel;
use App\Models\EstabelecimentoModel; // Importa o model para buscar o local
use CodeIgniter\RESTful\ResourceController;

class PesquisaController extends ResourceController
{
    // Define automaticamente o uso do PesquisaModel dentro do Controller
    protected $modelName = 'App\Models\PesquisaModel';
    protected $format = 'json';

    /**
     * GET /pesquisa
     * Renderiza a página do formulário buscando o nome do estabelecimento
     */
    public function index()
    {
        // Captura o 'token' ou o 'id' vindo na URL (?token=...)
        $token = $this->request->getGet('token') ?? $this->request->getGet('id');

        $estabelecimento = null;

        if ($token) {
            $estabelecimentoModel = new EstabelecimentoModel();

            // CORREÇÃO: Usando os nomes reais das suas colunas baseados no banco de dados
            $estabelecimento = $estabelecimentoModel->where('token_qr_code', $token)
                ->orWhere('id_estabelecimento', $token)
                ->first();
        }

        // Renderiza a view 'pesquisa' injetando os dados encontrados
        return view('pesquisa', [
            'estabelecimento' => $estabelecimento
        ]);
    }

    /**
     * POST /api/pesquisa
     * Salva a pesquisa enviada de forma inovadora pelo turista via JavaScript
     */
    /**
     * POST /api/pesquisa
     * Salva a pesquisa enviada de forma inovadora pelo turista via JavaScript
     */
    // --- MÓDULO 4: Carrega o Guia Turístico de Nova Lima ---
    public function guia()
    {
        $db = \Config\Database::connect();

        $estabelecimentos = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->get()
            ->getResultArray();

        return view('guia', ['estabelecimentos' => $estabelecimentos]);
    }

    // --- MÓDULO 4: Carrega a tela de Sucesso da Pesquisa ---
    public function sucesso()
    {
        return view('sucesso');
    }

    // --- MOTOR DE SUBMISSÃO DA PESQUISA ADAPTADO COM REDIRECIONAMENTO ---
    public function salvar()
    {
        try {
            // Captura o payload bruto enviado pelo front-end
            $dados = $this->request->getJSON(true);

            if (empty($dados)) {
                return $this->fail('Nenhum dado foi enviado no corpo da requisição.', 400);
            }

            // Converter o token recebido no ID numérico do banco de dados antes de salvar
            if (isset($dados['id_estabelecimento'])) {
                // Usando o Query Builder direto no banco para evitar erros de colunas fantasmas do Model
                $db = \Config\Database::connect();
                $local = $db->table('estabelecimento_evento')
                    ->where('token_qr_code', $dados['id_estabelecimento'])
                    ->orWhere('id_estabelecimento', $dados['id_estabelecimento'])
                    ->get()
                    ->getRowArray();

                if ($local) {
                    // Substitui o token texto pelo ID numérico real da chave estrangeira
                    $dados['id_estabelecimento'] = $local['id_estabelecimento'];
                } else {
                    return $this->failValidationErrors('O estabelecimento enviado não foi encontrado ou o token é inválido.');
                }
            }

            // TRADUÇÃO DO CAMPO: Mapeia e garante que o valor seja um número inteiro puro
            if (isset($dados['faixa_gasto'])) {
                $dados['valor_gasto_estimado'] = (int) $dados['faixa_gasto'];
                unset($dados['faixa_gasto']);
            }

            // Tratamento lógico de microconsistência: se for Bate e Volta, o local de hospedagem DEVE ser nulo
            if (isset($dados['tempo_permanencia']) && $dados['tempo_permanencia'] === 'bate_volta') {
                $dados['local_hospedagem'] = null;
            }

            // Executa a inserção passando pelas regras de validação do PesquisaModel
            if ($this->model->insert($dados)) {
                // Se a requisição foi AJAX/Fetch (esperando JSON), enviamos a instrução com a URL de redirecionamento
                if ($this->request->isAJAX() || strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
                    return $this->respondCreated([
                        'status' => 201,
                        'success' => true,
                        'message' => 'Pesquisa turística registrada com sucesso no ecossistema!',
                        'redirect' => base_url('pesquisa/sucesso') // URL de destino que o JavaScript usará para mudar a página
                    ]);
                }

                // Redirecionamento tradicional caso ocorra envio direto via POST do HTML
                return redirect()->to(base_url('pesquisa/sucesso'));
            }

            // Retorna os erros de validação estruturados (Ex: faltou o motivo da visita)
            return $this->failValidationErrors($this->model->errors());
        } catch (\Exception $e) {
            // Retorna o erro exato caso ainda falte algo no banco de dados (Ex: colunas de data)
            return $this->respond([
                'status' => 500,
                'error' => 500,
                'messages' => [
                    'error' => $e->getMessage() . ' no arquivo ' . $e->getFile() . ' na linha ' . $e->getLine()
                ]
            ], 500);
        }
    }
}