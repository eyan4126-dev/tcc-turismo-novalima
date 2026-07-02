<?php

namespace App\Controllers;

use App\Models\FluxoOcupacaoModel;
use CodeIgniter\RESTful\ResourceController;

class LojistaController extends ResourceController
{
    protected $format = 'json';

    // Exibe os dados do estabelecimento vinculado ao lojista logado e seu histórico
    public function index()
    {
        $idUsuarioLogado = session()->get('id_usuario');
        $db = \Config\Database::connect();

        // Encontra o estabelecimento do lojista
        $estabelecimento = $db->table('estabelecimento_evento')
            ->where('id_usuario', $idUsuarioLogado)
            ->get()
            ->getRowArray();

        if (!$estabelecimento) {
            return $this->failNotFound('Nenhum estabelecimento vinculado a esta conta de lojista.');
        }

        // Puxa o histórico de lançamentos dele
        $fluxoModel = new FluxoOcupacaoModel();
        $historico = $fluxoModel->where('id_estabelecimento', $estabelecimento['id_estabelecimento'])
            ->orderBy('data_referencia', 'DESC')
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'estabelecimento' => [
                'id_estabelecimento' => $estabelecimento['id_estabelecimento'],
                'razao_social'       => $estabelecimento['razao_social'],
                'setor'              => $estabelecimento['setor'],
                'token_qr_code'      => $estabelecimento['token_qr_code']
            ],
            'historico_lancamentos' => $historico
        ], 200);
    }
}
