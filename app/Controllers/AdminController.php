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

        // 1. CAPTURA DE FILTROS DA VIEW (GET)
        $idEvent = $this->request->getGet('id_evento');
        $periodo = $this->request->getGet('periodo') ?? 'atual'; // Padrão: mês corrente

        // Dropdown de Eventos (apenas lojistas ativos)
        $eventosFiltro = $db->table('estabelecimento_evento ee')
            ->join('usuario u', 'u.id_usuario = ee.id_usuario')
            ->where('u.status_usuario', 'ativo')
            ->where('ee.tipo', 'evento')
            ->get()->getResultArray() ?? [];

        // Inicialização das estruturas estruturadas para a View
        $kpis = ['impacto_economico' => 0, 'volume_turistico' => 0, 'ocupacao_hoteleira' => 0, 'satisfacao_media' => 0, 'nps' => 0];
        $charts = [
            'cidades' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'setores' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'motivos' => ['labels' => json_encode([]), 'valores' => json_encode([])],
            'hospedagem' => ['labels' => json_encode([]), 'valores' => json_encode([])]
        ];

        if ($db->tableExists('pesquisa')) {

            // FUNÇÃO FILTRO GLOBAL (Garante que KPIs e Gráficos usem o mesmo universo de dados)
            $aplicarRegrasCruzamento = function ($builder) use ($idEvent, $periodo) {
                $builder->join('estabelecimento_evento ee', 'ee.id_estabelecimento = p.id_estabelecimento')
                    ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                    ->where('u.status_usuario', 'ativo');

                if (!empty($idEvent)) {
                    $builder->where('ee.id_estabelecimento', $idEvent);
                }

                if ($periodo === 'atual') {
                    $primeiroDiaMes = date('Y-m-01 00:00:00');
                    $ultimoDiaMes = date('Y-m-t 23:59:59');
                    $builder->where("COALESCE(p.respondido_em, p.respondido_em) >=", $primeiroDiaMes)
                        ->where("COALESCE(p.respondido_em, p.respondido_em) <=", $ultimoDiaMes);
                }
                return $builder;
            };

            // --- CÁLCULO REAL DE KPIS (ADMIN) ---
            $kpis['impacto_economico'] = $aplicarRegrasCruzamento($db->table('pesquisa p')->selectSum('p.valor_gasto_estimado'))->get()->getRowArray()['valor_gasto_estimado'] ?? 0;
            $kpis['volume_turistico'] = $aplicarRegrasCruzamento($db->table('pesquisa p'))->countAllResults();
            $kpis['satisfacao_media'] = round($aplicarRegrasCruzamento($db->table('pesquisa p')->selectAvg('p.satisfacao_estrelas'))->get()->getRowArray()['satisfacao_estrelas'] ?? 0, 1);

            // KPI: Taxa de Ocupação Hoteleira (Fluxo Independente da pesquisa)
            if ($db->tableExists('fluxos_ocupacao')) {
                $bHotel = $db->table('fluxos_ocupacao fo')
                    ->join('estabelecimento_evento ee', 'ee.id_estabelecimento = fo.id_estabelecimento')
                    ->join('usuario u', 'u.id_usuario = ee.id_usuario')
                    ->where('u.status_usuario', 'ativo')
                    ->where('ee.setor', 'hospedagem');
                if (!empty($idEvent))
                    $bHotel->where('ee.id_estabelecimento', $idEvent);
                if ($periodo === 'atual') {
                    $bHotel->where("fo.criado_em >=", date('Y-m-01 00:00:00'))->where("fo.criado_em <=", date('Y-m-t 23:59:59'));
                }
                $hotelQuery = $bHotel->selectSum('fo.quartos_ocupados', 'oc')->selectSum('fo.capacidade_maxima_quartos', 'cap')->get()->getRowArray();
                if (($hotelQuery['cap'] ?? 0) > 0) {
                    $kpis['ocupacao_hoteleira'] = round(($hotelQuery['oc'] / $hotelQuery['cap']) * 100, 1);
                }
            }

            // KPI: Net Promoter Score Metódico
            $npsData = $aplicarRegrasCruzamento($db->table('pesquisa p')->select('p.nps'))->get()->getResultArray();
            if (count($npsData) > 0) {
                $promotores = 0;
                $detratores = 0;
                $foreachCount = 0;
                foreach ($npsData as $n) {
                    if ($n['nps'] >= 9)
                        $promotores++;
                    if ($n['nps'] <= 6)
                        $detratores++;
                    $foreachCount++;
                }
                $kpis['nps'] = round((($promotores - $detratores) / count($npsData)) * 100);
            }

            // --- PROCESSAMENTO DOS GRÁFICOS (ADMIN) ---
            $cidades = $aplicarRegrasCruzamento($db->table('pesquisa p')->select('p.cidade_origem, COUNT(*) as total')->groupBy('p.cidade_origem')->orderBy('total', 'DESC')->limit(5))->get()->getResultArray();
            $charts['cidades'] = ['labels' => json_encode(array_column($cidades, 'cidade_origem')), 'valores' => json_encode(array_map('intval', array_column($cidades, 'total')))];

            $setores = $aplicarRegrasCruzamento($db->table('pesquisa p')->select('ee.setor, COUNT(*) as total')->groupBy('ee.setor'))->get()->getResultArray();
            $charts['setores'] = ['labels' => json_encode(array_column($setores, 'setor')), 'valores' => json_encode(array_map('intval', array_column($setores, 'total')))];

            $motivos = $aplicarRegrasCruzamento($db->table('pesquisa p')->select('p.motivo_visita, COUNT(*) as total')->groupBy('p.motivo_visita'))->get()->getResultArray();
            $charts['motivos'] = ['labels' => json_encode(array_column($motivos, 'motivo_visita')), 'valores' => json_encode(array_map('intval', array_column($motivos, 'total')))];

            $hosp = $aplicarRegrasCruzamento($db->table('pesquisa p')->where('p.tempo_permanencia', 'dormir')->select('p.local_hospedagem, COUNT(*) as total')->groupBy('p.local_hospedagem'))->get()->getResultArray();
            $charts['hospedagem'] = ['labels' => json_encode(array_column($hosp, 'local_hospedagem')), 'valores' => json_encode(array_map('intval', array_column($hosp, 'total')))];
        }

        $solicitacoes = $db->table('usuario u')->join('estabelecimento_evento ee', 'ee.id_usuario = u.id_usuario')->where('u.status_usuario', 'pendente')->where('u.role_usuario', 'lojista')->get()->getResultArray() ?? [];

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

    // --- CADASTRO DIRETO DE PATRIMÔNIO MUNICIPAL (PREFEITURA) ---
    public function salvarDireto()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('estabelecimento_evento');

        // 1. TENTA CAPTURAR O ID DO ADMINISTRADOR CONECTADO NA SESSÃO
        $idUsuario = session()->get('id_usuario') ?? session()->get('id');

        // Fallback: Se a sessão do admin não estiver em conformidade (ou for teste), pegamos o ID do admin no banco
        if (!$idUsuario) {
            $adminUser = $db->table('usuario')->where('role_usuario', 'admin')->get()->getRowArray();
            $idUsuario = $adminUser ? $adminUser['id_usuario'] : null;
        }

        // Se mesmo assim não achar administrador, barramos o processo por integridade
        if (!$idUsuario) {
            return redirect()->to(site_url('admin'))->with('erro', 'Usuário administrador autorizador não foi localizado para o cadastro.');
        }

        $razaoSocial = $this->request->getPost('razao_social');
        $setor = $this->request->getPost('setor');
        $tipo = $this->request->getPost('tipo');
        $telefone = $this->request->getPost('telefone');

        $dataInicio = $this->request->getPost('data_inicio');
        $dataFim = $this->request->getPost('data_fim');

        // Geração limpa e padronizada do array de dados injetando o ID do usuário de posse pública
        $dados = [
            'id_usuario' => $idUsuario, // Injeta o ID da prefeitura, sanando de vez o erro MySQL #1048
            'razao_social' => $razaoSocial,
            'cnpj' => null, // Pontos públicos não exigem CNPJ de lojistas privados
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

        if ($builder->insert($dados)) {
            return redirect()->to(site_url('admin'))->with('sucesso', 'Patrimônio municipal cadastrado com sucesso!');
        } else {
            return redirect()->to(site_url('admin'))->with('erro', 'Falha ao salvar no banco de dados.');
        }
    }

    public function aprovarLojista($id = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('usuario')->where('id_usuario', $id)->update(['status_usuario' => 'ativo']);

        $db->table('estabelecimento_evento')->where('id_usuario', $id)->update([
            'token_qr_code' => bin2hex(random_bytes(10))
        ]);

        $db->transComplete();
        return redirect()->to(site_url('admin'));
    }

    public function recusarLojista($id = null)
    {
        $db = \Config\Database::connect();
        $db->table('usuario')->where('id_usuario', $id)->update(['status_usuario' => 'suspenso']);
        return redirect()->to(site_url('admin'));
    }

    /**
     * MÓDULO 2: Exportador Alinhado com o Leiaute do ICMS Turismo
     */
    public function exportarIcms()
    {
        $dataInicio = $this->request->getPost('data_inicio');
        $dataFim = $this->request->getPost('data_fim');

        if (!$dataInicio || !$dataFim) {
            return redirect()->back()->with('error', 'Período inválido para exportação fiscal.');
        }

        $pesquisaModel = new PesquisaModel();
        $dados = $pesquisaModel->getDadosFiscaisPorPeriodo($dataInicio, $dataFim);

        $nomeArquivo = 'icms_turismo_competencia_' . $dataInicio . '_a_' . $dataFim . '.csv';
        $this->_configurarHeadersCsv($nomeArquivo);

        $output = fopen("php://output", "w");

        // Injeções estruturais para compatibilidade MS Excel PT-BR automática
        fwrite($output, "\xEF\xBB\xBF");
        fwrite($output, "sep=;\n");

        fputcsv($output, ['ID_Amostra', 'Municipio_Origem', 'UF_Origem', 'Permanencia', 'Modalidade_Hospedagem', 'Gasto_Estimado_R$', 'Data_Registro'], ';');

        foreach ($dados as $linha) {
            // Remove aspas indesejadas vindas do banco de dados
            $cidadeCrua = str_replace(['"', "'"], '', $linha['cidade_origem'] ?? '');
            $partesOrigem = explode(' - ', $cidadeCrua);

            // Corrige a capitalização mantendo hifens inteligíveis (Ex: Xique-Xique)
            $cidade = mb_convert_case(trim($partesOrigem[0] ?? 'Não Informado'), MB_CASE_TITLE, "UTF-8");
            $cidade = implode('-', array_map('ucfirst', explode('-', $cidade)));

            // Estado sempre em Letras Maiúsculas Fixo (Ex: MG, BA)
            $uf = strtoupper(trim($partesOrigem[1] ?? 'MG'));

            $permanencia = ($linha['tempo_permanencia'] ?? '') === 'dormir' ? 'Pernoite' : 'Bate e Volta';

            $hospedagem = $linha['local_hospedagem'] ?? 'N/A';
            if ($hospedagem !== 'N/A') {
                $hospedagem = mb_convert_case(str_replace('_', ' ', $hospedagem), MB_CASE_TITLE, "UTF-8");
            }

            // Suporte nativo para a coluna correta 'respondido_em'
            $dataOriginal = $linha['respondido_em'] ?? $linha['created_at'] ?? null;
            $dataRegistro = $dataOriginal ? date('d/m/Y H:i', strtotime($dataOriginal)) : date('d/m/Y H:i');

            fputcsv($output, [
                $linha['id_pesquisa'] ?? '',
                $cidade,
                $uf,
                $permanencia,
                $hospedagem,
                number_format($linha['faixa_gasto'] ?? $linha['valor_gasto_estimado'] ?? 0, 2, ',', '.'),
                $dataRegistro
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
        $dataInicio = $this->request->getPost('data_inicio');
        $dataFim = $this->request->getPost('data_fim');

        if (!$dataInicio || !$dataFim) {
            return redirect()->back()->with('error', 'Período inválido para exportação Sismapa.');
        }

        $pesquisaModel = new PesquisaModel();
        $dados = $pesquisaModel->getDadosFiscaisPorPeriodo($dataInicio, $dataFim);

        $nomeArquivo = 'sismapa_matriz_fluxo_' . date('Ymd_His') . '.csv';
        $this->_configurarHeadersCsv($nomeArquivo);

        $output = fopen("php://output", "w");

        fwrite($output, "\xEF\xBB\xBF");
        fwrite($output, "sep=;\n");

        fputcsv($output, ['ID_Registro', 'ID_Estabelecimento_Coleta', 'Localidade_Turista', 'Motivacao_Visita', 'Tempo_Estadia', 'Grau_Satisfacao', 'Pontuacao_NPS'], ';');

        foreach ($dados as $linha) {
            $cidadeCrua = str_replace(['"', "'"], '', $linha['cidade_origem'] ?? '');

            // Corrige de forma isolada strings "Cidade - UF" para manter UF maiúscula e hifens intactos
            if (strpos($cidadeCrua, ' - ') !== false) {
                $partes = explode(' - ', $cidadeCrua);
                $cidadeTratada = mb_convert_case(trim($partes[0]), MB_CASE_TITLE, "UTF-8");
                $cidadeTratada = implode('-', array_map('ucfirst', explode('-', $cidadeTratada)));
                $ufTratada = strtoupper(trim($partes[1]));
                $localidadeFinal = $cidadeTratada . ' - ' . $ufTratada;
            } else {
                $localidadeFinal = mb_convert_case(trim($cidadeCrua), MB_CASE_TITLE, "UTF-8");
                $localidadeFinal = implode('-', array_map('ucfirst', explode('-', $localidadeFinal)));
            }

            $motivacao = mb_convert_case(str_replace('_', ' ', $linha['motivo_visita'] ?? ''), MB_CASE_TITLE, "UTF-8");
            $estadia = ($linha['tempo_permanencia'] ?? '') === 'dormir' ? 'Pernoite' : 'Bate e Volta';

            fputcsv($output, [
                $linha['id_pesquisa'] ?? '',
                $linha['id_estabelecimento'] ?? '',
                $localidadeFinal,
                $motivacao,
                $estadia,
                $linha['satisfacao_estrelas'] ?? '0',
                $linha['nps'] ?? '0'
            ], ';');
        }

        fclose($output);
        exit;
    }

    private function _configurarHeadersCsv($nomeArquivo)
    {
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$nomeArquivo");
        header("Content-Type: text/csv; charset=UTF-8");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}