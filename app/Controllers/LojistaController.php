<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstabelecimentoModel; // Importante para buscar os dados reais

class LojistaController extends BaseController
{
    public function index()
    {
        // KPIs fictícios focados no estabelecimento dele
        $data['kpis'] = [
            'impacto_economico' => 14250.80,
            'volume_turistico' => 384,
            'satisfacao_media' => 4.7,
            'nps' => 78
        ];

        // Gráficos estruturados e enxutos baseados nas colunas reais da pesquisa
        $data['charts'] = [
            'motivos' => [
                'labels' => json_encode(['Lazer/Turismo', 'Negócios', 'Eventos', 'Parentes/Amigos']),
                'valores' => json_encode([210, 45, 98, 31])
            ],
            'origem' => [
                'labels' => json_encode(['São Paulo', 'Belo Horizonte', 'Rio de Janeiro', 'Vitória', 'Outras']),
                'valores' => json_encode([150, 120, 64, 30, 20])
            ]
        ];

        return view('lojista/dashboard', $data);
    }

    public function qrcode()
    {
        $estabelecimentoModel = new EstabelecimentoModel();

        // Tenta resgatar o ID por qualquer um dos nomes que o seu Login possa ter usado
        $id_usuario_logado = session()->get('id_usuario')
            ?? session()->get('id')
            ?? session()->get('id_user');

        // Se mesmo testando os 3 nomes ainda vier vazio, vamos avisar exatamente o que está na sessão
        if (!$id_usuario_logado) {
            return "Erro de Sessão: Não foi encontrado nenhum ID de usuário logado. Conteúdo atual da sessão: " . print_r(session()->get(), true);
        }

        // Busca na tabela estabelecimento_evento usando o ID recuperado
        $estabelecimento = $estabelecimentoModel->where('id_usuario', $id_usuario_logado)->first();

        // Se o ID existir mas não achar o vínculo
        if (!$estabelecimento) {
            return "Erro de Vínculo: O usuário com o ID (" . esc($id_usuario_logado) . ") está logado, mas nenhuma linha na tabela 'estabelecimento_evento' aponta para este ID.";
        }

        // Passa o estabelecimento real encontrado ("Artesanatos de Nova Lima") para a View
        $data['estabelecimento'] = $estabelecimento;

        return view('lojista/qrcode', $data);
    }
}