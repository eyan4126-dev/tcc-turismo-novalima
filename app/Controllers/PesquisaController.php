<?php

namespace App\Controllers;

use App\Models\PesquisaModel;
use CodeIgniter\RESTful\ResourceController;

class PesquisaController extends ResourceController
{
    protected $format = 'json';

    public function salvar()
    {
        // 1. Regras de validação básicas para qualquer resposta
        $rules = [
            'id_estabelecimento'  => 'required|is_natural_no_zero',
            'cidade_origem'       => 'required|min_length[2]|max_length[100]',
            'tempo_permanencia'   => 'required|in_list[bate_volta,dormir]',
            'faixa_gasto'         => 'required|in_list[0_50,51_100,101_200,201_plus]',
            'satisfacao_estrelas' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'nps'                 => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]'
        ];

        // 2. Captura o tempo de permanência para aplicar a validação condicional
        $tempoPermanencia = $this->request->getVar('tempo_permanencia');

        if ($tempoPermanencia === 'dormir') {
            // Se dormiu em Nova Lima, o local de hospedagem é obrigatório e restrito ao ENUM
            $rules['local_hospedagem'] = 'required|in_list[hotel_pousada,airbnb_aluguel,casa_amigos_parentes,outro]';
        }

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // 3. Verifica se o estabelecimento recebido realmente existe no banco do Daniel
        $idEstabelecimento = $this->request->getVar('id_estabelecimento');
        $db = \Config\Database::connect();
        $existeEstabelecimento = $db->table('estabelecimentos')
            ->where('id', $idEstabelecimento)
            ->countAllResults();

        if ($existeEstabelecimento === 0) {
            return $this->failNotFound('QR Code inválido. O estabelecimento informado não está cadastrado.');
        }

        // 4. Prepara o payload limpando o local de hospedagem caso tenha sido um "Bate e Volta"
        $localHospedagem = ($tempoPermanencia === 'dormir') ? $this->request->getVar('local_hospedagem') : null;

        $dadosPesquisa = [
            'id_estabelecimento'  => $idEstabelecimento,
            'cidade_origem'       => strip_tags(trim($this->request->getVar('cidade_origem'))), // Sanitização simples
            'tempo_permanencia'   => $tempoPermanencia,
            'local_hospedagem'    => $localHospedagem,
            'faixa_gasto'         => $this->request->getVar('faixa_gasto'),
            'satisfacao_estrelas' => $this->request->getVar('satisfacao_estrelas'),
            'nps'                 => $this->request->getVar('nps')
        ];

        // 5. Salva no banco
        $model = new PesquisaModel();

        try {
            $model->insert($dadosPesquisa);

            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Pesquisa enviada com sucesso! Obrigado por contribuir com Nova Lima.'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno ao registrar as respostas: ' . $e->getMessage());
        }
    }
}
