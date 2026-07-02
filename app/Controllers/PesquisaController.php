<?php

namespace App\Controllers;

use App\Models\PesquisaModel;
use CodeIgniter\RESTful\ResourceController;

class PesquisaController extends ResourceController
{
    // Define automaticamente o uso do PesquisaModel dentro do Controller
    protected $modelName = 'App\Models\PesquisaModel';
    protected $format    = 'json';

    /**
     * POST /api/pesquisa
     * Salva a pesquisa enviada de forma inovadora pelo turista via JavaScript
     */
    public function salvar()
    {
        try {
            // Captura o payload bruto enviado pelo front-end
            $dados = $this->request->getJSON(true);

            if (empty($dados)) {
                return $this->fail('Nenhum dado foi enviado no corpo da requisição.', 400);
            }

            // Tratamento lógico de consistência: se for Bate e Volta, o local de hospedagem DEVE ser nulo
            if (isset($dados['tempo_permanencia']) && $dados['tempo_permanencia'] === 'bate_volta') {
                $dados['local_hospedagem'] = null;
            }

            // Executa a inserção passando pelas regras de validação do Model
            if ($this->model->insert($dados)) {
                return $this->respondCreated([
                    'status'  => 201,
                    'message' => 'Pesquisa turística registrada com sucesso no ecossistema!'
                ]);
            }

            // Retorna os erros de validação estruturados (Ex: faltou o motivo da visita)
            return $this->failValidationErrors($this->model->errors());
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno ao processar a pesquisa: ' . $e->getMessage());
        }
    }
}
