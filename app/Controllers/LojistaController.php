<?php

namespace App\Controllers;

use App\Models\FluxoOcupacaoModel;
use App\Models\UsuarioModel; // Assumindo que haja relação ou busca do estabelecimento
use CodeIgniter\RESTful\ResourceController;

class LojistaController extends ResourceController
{
    protected $format = 'json';

    public function lancarOcupacao()
    {
        // 1. Pega o ID do usuário logado direto da sessão segura (Filtro já garantiu que está logado)
        $idUsuarioLogado = session()->get('id');

        // 2. Busca o estabelecimento vinculado a este usuário para saber o SETOR
        // (Fazendo uma query direta na tabela estabelecimentos)
        $db = \Config\Database::connect();
        $estabelecimento = $db->table('estabelecimentos')
            ->where('id_usuario', $idUsuarioLogado)
            ->get()
            ->getRowArray();

        if (!$estabelecimento) {
            return $this->failNotFound('Estabelecimento não encontrado para este usuário.');
        }

        $idEstabelecimento = $estabelecimento['id'];
        $setor = $estabelecimento['setor'];

        // 3. Regras de validação básicas para TODOS os setores
        $rules = [
            'volume_clientes' => 'required|is_natural',
            'data_referencia' => 'required|valid_date[Y-m-d]'
        ];

        // 4. [CONDICIONAL] Se for hospedagem, injeta novas validações obrigatórias
        if ($setor === 'hospedagem') {
            $rules['quartos_ocupados'] = 'required|is_natural';
            $rules['capacidade_maxima_quartos'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // 5. Coleta os dados enviados pelo Front-end do Waron
        $volumeClientes = $this->request->getVar('volume_clientes');
        $dataReferencia = $this->request->getVar('data_referencia');

        $quartosOcupados = null;
        $capacidadeMaxima = null;

        if ($setor === 'hospedagem') {
            $quartosOcupados = $this->request->getVar('quartos_ocupados');
            $capacidadeMaxima = $this->request->getVar('capacidade_maxima_quartos');

            // Regra de consistência: Não dá para ocupar mais quartos do que a pousada possui
            if ($quartosOcupados > $capacidadeMaxima) {
                return $this->fail('A quantidade de quartos ocupados não pode ser maior que a capacidade máxima.', 400);
            }
        }

        // 6. Monta o payload definitivo para o banco do Daniel
        $dadosParaSalvar = [
            'id_estabelecimento'        => $idEstabelecimento,
            'volume_clientes'           => $volumeClientes,
            'quartos_ocupados'          => $quartosOcupados,
            'capacidade_maxima_quartos' => $capacidadeMaxima,
            'data_referencia'           => $dataReferencia
        ];

        $model = new FluxoOcupacaoModel();

        try {
            $model->insert($dadosParaSalvar);

            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Fluxo de ocupação registrado com sucesso!',
                'setor_processado' => $setor
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Erro ao salvar os dados no banco: ' . $e->getMessage());
        }
    }
}
