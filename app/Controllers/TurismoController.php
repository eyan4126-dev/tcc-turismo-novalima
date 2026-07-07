<?php

namespace App\Controllers;

use App\Models\EstabelecimentoModel;

class TurismoController extends BaseController
{
    public function validarToken($token)
    {
        $estabelecimentoModel = new EstabelecimentoModel();

        // Busca o estabelecimento pelo token gerado no banco
        $estabelecimento = $estabelecimentoModel->where('token_qr_code', $token)->first();

        // Se o token for inválido ou falsificado
        if (!$estabelecimento) {
            return "<h1>Erro: QR Code inválido ou não reconhecido pelo sistema de turismo.</h1>";
        }

        // Se achou, passa os dados do restaurante/pousada para a view do turista
        $data['estabelecimento'] = $estabelecimento;

        return view('turismo/perfil_estabelecimento', $data);
    }
}