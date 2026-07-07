<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstabelecimentoModel;

class LojistaController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // RECUPERA O LOJISTA LOGADO PELA SESSÃO REAL DO SISTEMA
        $idUsuarioLogado = session()->get('id_usuario') ?? 1; // Fallback temporário para testes se necessário

        // Filtro Temporal enviado pelo form da View do Lojista
        $periodo = $this->request->getGet('periodo') ?? 'atual';

        // Estrutura de KPIs e Gráficos micro-estratégicos
        $kpis = ['faturamento_estimado' => 0, 'volume_clientes' => 0, 'ticket_medio' => 0, 'satisfacao_exclusiva' => 0, 'nps_proprio' => 0];
        $charts = [
            'cidades' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'permanencia' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'motivos' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'hospedagem' => ['labels' => json_encode([]), 'valores' => json_encode([])]
        ];

        if ($db->tableExists('pesquisa')) {

            /**
             * TRAVA DE SEGURANÇA E AMARRAÇÃO DO LOJISTA:
             * Cruza a pesquisa com o estabelecimento cujo dono é o ID da Sessão logada.
             */
            $aplicarFiltroLojista = function ($builder) use ($idUsuarioLogado, $periodo) {
                $builder->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                    ->where('ee.id_usuario', $idUsuarioLogado);

                if ($periodo === 'atual') {
                    $builder->where("COALESCE(p.respondido_em, p.created_at) >=", date('Y-m-01 00:00:00'))
                        ->where("COALESCE(p.respondido_em, p.created_at) <=", date('Y-m-t 23:59:59'));
                }
                return $builder;
            };

            // --- CÁLCULO DE KPIS EXCLUSIVOS DO LOJISTA ---

            // Faturamento Capturado dentro da loja dele
            $kpis['faturamento_estimado'] = $aplicarFiltroLojista($db->table('pesquisa p')->selectSum('p.valor_gasto_estimado'))->get()->getRowArray()['valor_gasto_estimado'] ?? 0;

            // Quantidade de Clientes que leram o QR Code dele
            $kpis['volume_clientes'] = $aplicarFiltroLojista($db->table('pesquisa p'))->countAllResults();

            // Cálculo do Ticket Médio Real (Faturamento / Volume)
            if ($kpis['volume_clientes'] > 0) {
                $kpis['ticket_medio'] = round($kpis['faturamento_estimado'] / $kpis['volume_clientes'], 2);
            }

            // Nota de Satisfação Interna do Estabelecimento
            $kpis['satisfacao_exclusiva'] = round($aplicarFiltroLojista($db->table('pesquisa p')->selectAvg('p.satisfacao_estrelas'))->get()->getRowArray()['satisfacao_estrelas'] ?? 0, 1);

            // NPS Privado da Loja
            $npsData = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.nps'))->get()->getResultArray();
            if (count($npsData) > 0) {
                $p = 0;
                $d = 0;
                foreach ($npsData as $n) {
                    if ($n['nps'] >= 9)
                        $p++;
                    if ($n['nps'] <= 6)
                        $d++;
                }
                $kpis['nps_proprio'] = round((($p - $d) / count($npsData)) * 100);
            }

            // --- GRÁFICOS DE INTELIGÊNCIA COMPETITIVA (LOJISTA) ---

            // De onde vêm as pessoas que compram na minha loja?
            $cidades = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.cidade_origem, COUNT(*) as total')->groupBy('p.cidade_origem')->orderBy('total', 'DESC')->limit(5))->get()->getResultArray();
            $charts['cidades'] = ['labels' => json_encode(array_column($cidades, 'cidade_origem')), 'valores' => json_encode(array_map('intval', array_column($cidades, 'total')))];

            // Meu cliente dorme na cidade ou faz bate-volta? (Ajuste de horário de funcionamento)
            $permanencia = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.tempo_permanencia, COUNT(*) as total')->groupBy('p.tempo_permanencia'))->get()->getResultArray();
            $charts['permanencia'] = ['labels' => json_encode(array_column($permanencia, 'tempo_permanencia')), 'valores' => json_encode(array_map('intval', array_column($permanencia, 'total')))];

            // O que trouxe meu cliente para a cidade?
            $motivos = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.motivo_visita, COUNT(*) as total')->groupBy('p.motivo_visita'))->get()->getResultArray();
            $charts['motivos'] = ['labels' => json_encode(array_column($motivos, 'motivo_visita')), 'valores' => json_encode(array_map('intval', array_column($motivos, 'total')))];

            // Onde meu cliente se hospeda? (Para fazer parcerias de panfletagem/indicação)
            $hosp = $aplicarFiltroLojista($db->table('pesquisa p')->where('p.tempo_permanencia', 'dormir')->select('p.local_hospedagem, COUNT(*) as total')->groupBy('p.local_hospedagem'))->get()->getResultArray();
            $charts['hospedagem'] = ['labels' => json_encode(array_column($hosp, 'local_hospedagem')), 'valores' => json_encode(array_map('intval', array_column($hosp, 'total')))];
        }

        return view('lojista/dashboard', [
            'kpis' => $kpis,
            'charts' => $charts
        ]);
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