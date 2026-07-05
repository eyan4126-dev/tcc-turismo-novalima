<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class AdminController extends BaseController
{
    protected $helpers = ['url'];

    public function index()
    {
        $db = \Config\Database::connect();
        $userModel = new UsuarioModel();

        // 1. Filtro de Status Implícito: Apenas dados vinculados a usuários ATIVOS
        $idEvento = $this->request->getGet('id_evento');

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

            if ($idEvento) {
                $builder->where('ee.id_estabelecimento', $idEvento);
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
                    if ($n['nps'] >= 9) $p++;
                    if ($n['nps'] <= 6) $d++;
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
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Cria o usuário do tipo admin associado ao cadastro direto efetuado
        $db->table('usuario')->insert([
            'nome_responsavel' => 'Funcionário Prefeitura (Admin)',
            'email' => 'admin_direto_' . time() . '@novalima.mg.gov.br',
            'senha' => password_hash(bin2hex(random_bytes(4)), PASSWORD_BCRYPT),
            'role_usuario' => 'admin',
            'status_usuario' => 'ativo' // Ativado imediatamente
        ]);
        $idUsuario = $db->insertID();

        // 2. Persiste o estabelecimento/ponto gerando o token_qr_code criptográfico seguro
        $db->table('estabelecimento_evento')->insert([
            'id_usuario' => $idUsuario,
            'razao_social' => $this->request->getPost('razao_social'),
            'cnpj' => null, // Conforme especificação: NULL para cadastros públicos da prefeitura
            'telefone' => $this->request->getPost('telefone'),
            'setor' => $this->request->getPost('setor'),
            'token_qr_code' => bin2hex(random_bytes(10)), // Token não sequencial de alta entropia
            'tipo' => $this->request->getPost('tipo'),
            'data_inicio' => $this->request->getPost('data_inicio') ?: null,
            'data_fim' => $this->request->getPost('data_fim') ?: null,
        ]);

        $db->transComplete();
        return redirect()->to('/estabelecimentos');
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
}
