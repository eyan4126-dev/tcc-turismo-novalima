<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\PesquisaModel;

class AdminController extends BaseController
{
    protected $helpers = ['url'];

    public function index()
    {
        $db = \Config\Database::connect();
        $userModel = new UsuarioModel();

        // 1. Filtro de Status Implícito: Apenas dados vinculados a usuários ATIVOS
        $idEvent = $this->request->getGet('id_evento');

        // Seleção de filtros de evento sazonal para carregar no dropdown da View
        $eventosFiltro = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->where('ee.tipo', 'evento')
            ->get()->getResultArray() ?? [];

        // Inicialização de estrutura de KPIs em conformidade com as regras de cálculo do TCC v2
        $kpis = ['impacto_economico' => 0, 'volume_turistico' => 0, 'ocupacao_hoteleira' => 0, 'satisfacao_media' => 0, 'nps' => 0];
        $charts = [
            'cidades' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'setores' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'motivos' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'hospedagem' => ['labels' => json_encode([]), 'valores' => json_encode([])]
        ];

        if ($db->tableExists('pesquisa')) {
            // Montagem da query base com filtros implícitos estruturados
            $builder = $db->table('pesquisa p')
                ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                ->where('u.status_usuario', 'ativo');

            if ($idEvent) {
                $builder->where('ee.id_estabelecimento', $idEvent);
            }

            // --- CÁLCULO DE KPIs ---
            // 1. Impacto Econômico: SUM(valor_gasto_estimado) + Fluxos de lojistas
            $kpis['impacto_economico'] = $builder->selectSum('p.valor_gasto_estimado')->get()->getRowArray()['valor_gasto_estimado'] ?? 0;

            // 2. Volume Turístico Geral (Pesquisas + Fluxos)
            $totalRespostas = $db->table('pesquisa p')
                ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                ->where('u.status_usuario', 'ativo')
                ->countAllResults();
            $kpis['volume_turistico'] = $totalRespostas;

            // 3. Taxa de Ocupação Hoteleira
            if ($db->tableExists('fluxos_ocupacao')) {
                $hotelQuery = $db->table('fluxos_ocupacao fo')
                    ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = fo.id_estabelecimento')
                    ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                    ->where('u.status_usuario', 'ativo')
                    ->where('ee.setor', 'hospedagem')
                    ->selectSum('fo.quartos_ocupados', 'oc')
                    ->selectSum('fo.capacidade_maxima_quartos', 'cap')
                    ->get()->getRowArray();
                if (($hotelQuery['cap'] ?? 0) > 0) {
                    $kpis['ocupacao_hoteleira'] = round(($hotelQuery['oc'] / $hotelQuery['cap']) * 100, 1);
                }
            }

            // 4. Índice de Satisfação Média (Estrelas)
            $kpis['satisfacao_media'] = round($db->table('pesquisa p')
                ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                ->where('u.status_usuario', 'ativo')
                ->selectAvg('p.satisfacao_estrelas')
                ->get()->getRowArray()['satisfacao_estrelas'] ?? 0, 1);

            // 5. Cálculo Convencional de Mercado para NPS (% Promotores - % Detratores)
            $npsData = $db->table('pesquisa p')
                ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                ->where('u.status_usuario', 'ativo')
                ->select('p.nps')->get()->getResultArray();
            if (count($npsData) > 0) {
                $p = 0;
                $d = 0;
                foreach ($npsData as $n) {
                    if ($n['nps'] >= 9)
                        $p++;
                    if ($n['nps'] <= 6)
                        $d++;
                }
                $kpis['nps'] = round((($p - $d) / count($npsData)) * 100);
            }

            // --- GRÁFICOS COMPORTAMENTAIS (População dos Arrays de Exibição) ---
            // Cidades Origem (Top 5)
            $cidades = $db->table('pesquisa p')->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')->join('usuario u', 'u.id_usuario = ee.id_usuario')->where('u.status_usuario', 'ativo')->select('p.cidade_origem, COUNT(*) as total')->groupBy('p.cidade_origem')->orderBy('total', 'DESC')->limit(5)->get()->getResultArray();
            $charts['cidades'] = ['labels' => json_encode(array_column($cidades, 'cidade_origem')), 'valores' => json_encode(array_map('intval', array_column($cidades, 'total')))];

            // Setores
            $setores = $db->table('pesquisa p')->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')->join('usuario u', 'u.id_usuario = ee.id_usuario')->where('u.status_usuario', 'ativo')->select('ee.setor, COUNT(*) as total')->groupBy('ee.setor')->get()->getResultArray();
            $charts['setores'] = ['labels' => json_encode(array_column($setores, 'setor')), 'valores' => json_encode(array_map('intval', array_column($setores, 'total')))];

            // Motivos da Visita
            $motivos = $db->table('pesquisa p')->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')->join('usuario u', 'u.id_usuario = ee.id_usuario')->where('u.status_usuario', 'ativo')->select('p.motivo_visita, COUNT(*) as total')->groupBy('p.motivo_visita')->get()->getResultArray();
            $charts['motivos'] = ['labels' => json_encode(array_column($motivos, 'motivo_visita')), 'valores' => json_encode(array_map('intval', array_column($motivos, 'total')))];

            // Perfil de Hospedagem (Apenas tempo_permanencia = 'dormir')
            $hosp = $db->table('pesquisa p')->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')->join('usuario u', 'u.id_usuario = ee.id_usuario')->where('u.status_usuario', 'ativo')->where('p.tempo_permanencia', 'dormir')->select('p.local_hospedagem, COUNT(*) as total')->groupBy('p.local_hospedagem')->get()->getResultArray();
            $charts['hospedagem'] = ['labels' => json_encode(array_column($hosp, 'local_hospedagem')), 'valores' => json_encode(array_map('intval', array_column($hosp, 'total')))];
        }

        // Carrega o Onboarding das solicitações pendentes de Lojistas
        $solicitacoes = $db->table('usuario u')
            ->join('estabelecimento_evento ee', 'ee.id_usuario = u.id_usuario')
            ->where('u.status_usuario', 'pendente')
            ->where('u.role_usuario', 'lojista')
            ->get()->getResultArray() ?? [];

        return view('admin', [
            'eventosFiltro' => $eventosFiltro,
            'kpis' => $kpis,
            'charts' => $charts,
            'solicitacoes' => $solicitacoes
        ]);
    }

    public function estabelecimentos()
    {
        $db = \Config\Database::connect();
        $estabelecimentos = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->get()->getResultArray() ?? [];

        return view('estabelecimentos', ['estabelecimentos' => $estabelecimentos]);
    }

    public function qrcodes()
    {
        $db = \Config\Database::connect();
        $qrcodes = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->get()->getResultArray() ?? [];

        return view('qrcodes', ['qrcodes' => $qrcodes]);
    }

    public function salvarDireto()
    {
        // Pega a conexão direta com o banco de dados (ignora as travas automáticas do Model)
        $db = \Config\Database::connect();
        $builder = $db->table('estabelecimento_evento');

        $razaoSocial = $this->request->getPost('razao_social');
        $setor = $this->request->getPost('setor');
        $tipo = $this->request->getPost('tipo');
        $telefone = $this->request->getPost('telefone');

        $dataInicio = $this->request->getPost('data_inicio');
        $dataFim = $this->request->getPost('data_fim');

        // Estrutura de dados simplificada e direta
        $dados = [
            'id_usuario' => null,
            'razao_social' => $razaoSocial,
            'cnpj' => null,
            'telefone' => $telefone,
            'setor' => $setor,
            'tipo' => $tipo,
            'token_qr_code' => md5(uniqid($razaoSocial, true))
        ];

        if ($tipo === 'evento') {
            $dados['data_inicio'] = !empty($dataInicio) ? $dataInicio : null;
            $dados['data_fim'] = !empty($dataFim) ? $dataFim : null;
        } else {
            $dados['data_inicio'] = null;
            $dados['data_fim'] = null;
        }

        // Insere diretamente via Query Builder
        if ($builder->insert($dados)) {
            return redirect()->to(base_url('admin'))->with('sucesso', 'Atrativo/Evento cadastrado com sucesso!');
        } else {
            return redirect()->to(base_url('admin'))->with('erro', 'Falha ao salvar no banco de dados.');
        }
    }

    public function aprovarLojista($id = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('usuario')->where('id_usuario', $id)->update(['status_usuario' => 'ativo']);

        // Gera o token de segurança para o QR Code no momento exato de sua ativação
        $db->table('estabelecimento_evento')->where('id_usuario', $id)->update([
            'token_qr_code' => bin2hex(random_bytes(10))
        ]);

        $db->transComplete();
        return redirect()->to('/admin');
    }

    public function recusarLojista($id = null)
    {
        $db = \Config\Database::connect();
        $db->table('usuario')->where('id_usuario', $id)->update(['status_usuario' => 'suspenso']);
        return redirect()->to('/admin');
    }

    // ==========================================
    //  IMPLEMENTAÇÃO NOVA: MÓDULO DE PRESTAÇÃO
    // ==========================================

    /**
     * MÓDULO 2: Renderiza a tela de Prestação de Contas Estaduais
     */
    public function prestacaoContas()
    {
        return view('admin/prestacao_contas');
    }

    /**
     * MÓDULO 2: Exportador Alinhado com o Leiaute do ICMS Turismo
     */
    public function exportarIcmsTurismo()
    {
        $dataInicio = $this->request->getGet('data_inicio');
        $dataFim = $this->request->getGet('data_fim');

        if (!$dataInicio || !$dataFim) {
            return redirect()->back()->with('error', 'Período inválido para exportação fiscal.');
        }

        $pesquisaModel = new PesquisaModel();
        $dados = $pesquisaModel->getDadosFiscaisPorPeriodo($dataInicio, $dataFim);

        $nomeArquivo = 'icms_turismo_competencia_' . $dataInicio . '_a_' . $dataFim . '.csv';
        $this->_configurarHeadersCsv($nomeArquivo);

        $output = fopen("php://output", "w");
        fwrite($output, "\xEF\xBB\xBF"); // Injeta o BOM para compatibilidade MS Excel em PT-BR

        // Estrutura de colunas exigida para auditorias do critério ICMS Turismo
        fputcsv($output, ['ID_Amostra', 'Municipio_Origem', 'UF_Origem', 'Permanencia', 'Modalidade_Hospedagem', 'Gasto_Estimado_R$', 'Data_Registro'], ';');

        foreach ($dados as $linha) {
            $partesOrigem = explode(' - ', $linha['cidade_origem']);
            $cidade = $partesOrigem[0] ?? $linha['cidade_origem'];
            $uf = $partesOrigem[1] ?? 'MG';

            fputcsv($output, [
                $linha['id_pesquisa'],
                $cidade,
                $uf,
                $linha['tempo_permanencia'] === 'dormir' ? 'Pernoite' : 'Bate e Volta',
                $linha['local_hospedagem'] ?? 'N/A',
                number_format($linha['faixa_gasto'] ?? $linha['valor_gasto_estimado'] ?? 0, 2, ',', '.'),
                date('d/m/Y H:i', strtotime($linha['created_at']))
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * MÓDULO 2: Exportador Alinhado com a Matriz Sismapa / Secult
     */
    public function exportarSismapa()
    {
        $dataInicio = $this->request->getGet('data_inicio');
        $dataFim = $this->request->getGet('data_fim');

        if (!$dataInicio || !$dataFim) {
            return redirect()->back()->with('error', 'Período inválido para exportação Sismapa.');
        }

        $pesquisaModel = new PesquisaModel();
        $dados = $pesquisaModel->getDadosFiscaisPorPeriodo($dataInicio, $dataFim);

        $nomeArquivo = 'sismapa_matriz_fluxo_' . date('Ymd_His') . '.csv';
        $this->_configurarHeadersCsv($nomeArquivo);

        $output = fopen("php://output", "w");
        fwrite($output, "\xEF\xBB\xBF");

        // Layout de exportação para Inventário de Mapeamento Regional Sismapa
        fputcsv($output, ['ID_Registro', 'ID_Estabelecimento_Coleta', 'Localidade_Turista', 'Motivacao_Visita', 'Tempo_Estadia', 'Grau_Satisfacao', 'Pontuacao_NPS'], ';');

        foreach ($dados as $linha) {
            fputcsv($output, [
                $linha['id_pesquisa'],
                $linha['id_estabelecimento'],
                $linha['cidade_origem'],
                $linha['motivo_visita'],
                $linha['tempo_permanencia'],
                $linha['satisfacao_estrelas'],
                $linha['nps']
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * Método auxiliar privado para gerenciar os cabeçalhos HTTP de download do arquivo CSV
     */
    private function _configurarHeadersCsv($nomeArquivo)
    {
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$nomeArquivo");
        header("Content-Type: text/csv; charset=UTF-8");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}