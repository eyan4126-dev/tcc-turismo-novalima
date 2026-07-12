<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstabelecimentoModel;

class LojistaController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. RECUPERA O LOJISTA LOGADO PELA SESSÃO REAL
        $idUsuarioLogado = session()->get('id');

        // 2. BUSCA O ESTABELECIMENTO EM TEMPO REAL PARA GARANTIR DADOS FRESCOS
        $estabelecimentoLogado = $db->table('estabelecimento_evento')
            ->where('id_usuario', $idUsuarioLogado)
            ->get()
            ->getRowArray();

        // Fallback caso não encontre o vínculo no banco, para não quebrar a página
        $setorReal = $estabelecimentoLogado['setor'] ?? 'outro';
        $aceitaDesconto = $estabelecimentoLogado['aceita_desconto'] ?? 0;
        $descontoPercentagem = $estabelecimentoLogado['desconto_percentagem'] ?? 10; // Força fallback para 10%
        $pinValidacao = $estabelecimentoLogado['pin_validacao'] ?? 'Pendente';
        $fotoAtual = $estabelecimentoLogado['foto'] ?? null;

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
                    $builder->where("COALESCE(p.respondido_em, p.respondido_em) >=", date('Y-m-01 00:00:00'))
                        ->where("COALESCE(p.respondido_em, p.respondido_em) <=", date('Y-m-t 23:59:59'));
                }
                return $builder;
            };

            // --- CÁLCULO DE KPIS EXCLUSIVOS DO LOJISTA ---
            $kpis['faturamento_estimado'] = $aplicarFiltroLojista($db->table('pesquisa p')->selectSum('p.valor_gasto_estimado'))->get()->getRowArray()['valor_gasto_estimado'] ?? 0;
            $kpis['volume_clientes'] = $aplicarFiltroLojista($db->table('pesquisa p'))->countAllResults();

            if ($kpis['volume_clientes'] > 0) {
                $kpis['ticket_medio'] = round($kpis['faturamento_estimado'] / $kpis['volume_clientes'], 2);
            }

            $kpis['satisfacao_exclusiva'] = round($aplicarFiltroLojista($db->table('pesquisa p')->selectAvg('p.satisfacao_estrelas'))->get()->getRowArray()['satisfacao_estrelas'] ?? 0, 1);

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
            $cidades = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.cidade_origem, COUNT(*) as total')->groupBy('p.cidade_origem')->orderBy('total', 'DESC')->limit(5))->get()->getResultArray();
            $charts['cidades'] = ['labels' => json_encode(array_column($cidades, 'cidade_origem')), 'valores' => json_encode(array_map('intval', array_column($cidades, 'total')))];

            $permanencia = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.tempo_permanencia, COUNT(*) as total')->groupBy('p.tempo_permanencia'))->get()->getResultArray();
            $charts['permanencia'] = ['labels' => json_encode(array_column($permanencia, 'tempo_permanencia')), 'valores' => json_encode(array_map('intval', array_column($permanencia, 'total')))];

            $motivos = $aplicarFiltroLojista($db->table('pesquisa p')->select('p.motivo_visita, COUNT(*) as total')->groupBy('p.motivo_visita'))->get()->getResultArray();
            $charts['motivos'] = ['labels' => json_encode(array_column($motivos, 'motivo_visita')), 'valores' => json_encode(array_map('intval', array_column($motivos, 'total')))];

            $hosp = $aplicarFiltroLojista($db->table('pesquisa p')->where('p.tempo_permanencia', 'dormir')->select('p.local_hospedagem, COUNT(*) as total')->groupBy('p.local_hospedagem'))->get()->getResultArray();
            $charts['hospedagem'] = ['labels' => json_encode(array_column($hosp, 'local_hospedagem')), 'valores' => json_encode(array_map('intval', array_column($hosp, 'total')))];
        }

        // 3. ENVIA TUDO PARA A VIEW, INCLUINDO AS PORCENTAGENS DO DESCONTOUR
        return view('lojista/dashboard', [
            'kpis' => $kpis,
            'charts' => $charts,
            'setorLojista' => $setorReal,
            'aceitaDesconto' => $aceitaDesconto,
            'descontoPercentagem' => $descontoPercentagem,
            'pinValidacao' => $pinValidacao,
            'fotoAtual' => $fotoAtual,
            'razaoSocial' => $estabelecimentoLogado['razao_social'] ?? 'Meu Negócio'
        ]);
    }

    public function qrcode()
    {
        $estabelecimentoModel = new EstabelecimentoModel();
        $id_usuario_logado = session()->get('id_usuario') ?? session()->get('id');

        if (!$id_usuario_logado) {
            return "Erro de Sessão: Usuário não identificado.";
        }

        // Busca fresca do estabelecimento
        $estabelecimento = $estabelecimentoModel->where('id_usuario', $id_usuario_logado)->first();

        if (!$estabelecimento) {
            return "Erro de Vínculo: Estabelecimento não encontrado.";
        }

        $data['estabelecimento'] = $estabelecimento;
        return view('lojista/qrcode', $data);
    }

    public function atualizarDesconto()
    {
        $idUsuarioLogado = session()->get('id');
        $estabelecimentoModel = new EstabelecimentoModel();

        // Busca o estabelecimento atual
        $estabelecimento = $estabelecimentoModel->where('id_usuario', $idUsuarioLogado)->first();

        if (!$estabelecimento) {
            return redirect()->back()->with('error', 'Estabelecimento não vinculado ao seu usuário.');
        }

        $aceitaDesconto = $this->request->getPost('aceita_desconto') !== null ? 1 : 0;

        // Pega a porcentagem do formulário. Se for inativo, mantém o valor antigo no banco ou 10
        $descontoPercentagem = $this->request->getPost('desconto_percentagem') !== null
            ? (int) $this->request->getPost('desconto_percentagem')
            : (int) ($estabelecimento['desconto_percentagem'] ?? 10);

        // Força a validação de regras de negócio antes de gravar
        if ($aceitaDesconto == 1) {
            if ($descontoPercentagem < 5) {
                return redirect()->back()->with('error', 'O desconto mínimo aceito no Programa DesconTour é de 5%.');
            }
            if ($descontoPercentagem > 100) {
                return redirect()->back()->with('error', 'O desconto máximo aceito é de 100%.');
            }
        }

        $pinAtual = $estabelecimento['pin_validacao'];
        if ($aceitaDesconto == 1 && empty($pinAtual)) {
            $pinAtual = sprintf("%04d", mt_rand(0, 9999));
        }

        // Prepara o array com tipos primitivos exatos para o MySQL
        $dadosUpdate = [
            'aceita_desconto' => (int) $aceitaDesconto,
            'desconto_percentagem' => (int) $descontoPercentagem,
            'pin_validacao' => $pinAtual
        ];

        // --- SISTEMA DE UPLOAD FOTO (CI4) ---
        $img = $this->request->getFile('foto_estabelecimento');

        if ($img && $img->isValid() && !$img->hasMoved()) {
            $validationRules = [
                'foto_estabelecimento' => [
                    'rules' => 'uploaded[foto_estabelecimento]|is_image[foto_estabelecimento]|max_size[foto_estabelecimento,4096]',
                    'label' => 'Foto do Estabelecimento'
                ]
            ];

            if ($this->validate($validationRules)) {
                $nomeUnico = $img->getRandomName();
                $caminhoDestino = FCPATH . 'uploads/estabelecimentos';

                // Cria a pasta se não existir física no servidor
                if (!is_dir($caminhoDestino)) {
                    @mkdir($caminhoDestino, 0777, true);
                }

                $img->move($caminhoDestino, $nomeUnico);

                if (!empty($estabelecimento['foto']) && file_exists($caminhoDestino . '/' . $estabelecimento['foto'])) {
                    @unlink($caminhoDestino . '/' . $estabelecimento['foto']);
                }

                $dadosUpdate['foto'] = $nomeUnico;
            } else {
                return redirect()->back()->with('error', 'Formato de imagem inválido ou tamanho limite de 4MB excedido.');
            }
        }

        // Executa o update usando o Model (que agora permite salvar desconto_percentagem!)
        if ($estabelecimentoModel->update($estabelecimento['id_estabelecimento'], $dadosUpdate)) {
            return redirect()->back()->with('sucesso', 'Configurações do Programa DesconTour salvas com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possível atualizar as configurações no banco de dados.');
        }
    }

    public function lancarOcupacao()
    {
        $db = \Config\Database::connect();
        $fluxoModel = new \App\Models\FluxoOcupacaoModel();

        $idUsuarioLogado = session()->get('id_usuario') ?? session()->get('id');

        $estabelecimento = $db->table('estabelecimento_evento')
            ->where('id_usuario', $idUsuarioLogado)
            ->get()
            ->getRowArray();

        if (!$estabelecimento) {
            return redirect()->back()->with('error', 'Estabelecimento não vinculado ao seu usuário.');
        }

        $mesAno = $this->request->getPost('data_referencia');
        $dataReferenciaFormatted = $mesAno . '-01';

        $payload = [
            'id_estabelecimento' => $estabelecimento['id_estabelecimento'],
            'volume_clientes' => (int) $this->request->getPost('volume_clientes'),
            'quartos_ocupados' => (int) $this->request->getPost('quartos_ocupados'),
            'capacidade_maxima_quartos' => (int) $this->request->getPost('capacidade_maxima_quartos'),
            'data_referencia' => $dataReferenciaFormatted
        ];

        try {
            $registroExistente = $fluxoModel->where('id_estabelecimento', $estabelecimento['id_estabelecimento'])
                ->where('data_referencia', $dataReferenciaFormatted)
                ->first();

            if ($registroExistente) {
                $fluxoModel->update($registroExistente['id_fluxos'], $payload);
                return redirect()->back()->with('success', 'Dados de desempenho operacional atualizados com sucesso!');
            } else {
                $fluxoModel->insert($payload);
                return redirect()->back()->with('success', 'Dados de desempenho operacional gravados com sucesso!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro interno ao salvar: ' . $e->getMessage());
        }
    }
}