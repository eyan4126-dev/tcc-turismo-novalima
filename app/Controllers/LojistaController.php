<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LojistaController extends BaseController
{
    public function index()
    {
        // Exemplo de resgate do ID do estabelecimento logado via sessão
        // $idEstabelecimento = session()->get('id_estabelecimento');

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
        // Dados básicos que existem no seu banco para o lojista conferir e baixar o QR
        $data['estabelecimento'] = [
            'razao_social' => 'Restaurante Sabor & Arte',
            'cnpj' => '12.345.678/0001-99',
            'setor' => 'Alimentação / Gastronomia',
            'qr_code_url' => 'public/qrcodes/exemplo.png' // Caminho do QR Code gerado na aprovação
        ];

        return view('lojista/qrcode', $data);
    }
}
