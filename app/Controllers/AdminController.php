<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\EstabelecimentoModel;
use CodeIgniter\RESTful\ResourceController;

class AdminController extends ResourceController
{
    protected $format = 'json';

    // Lista lojistas aguardando aprovação da prefeitura
    public function listarPendentes()
    {
        $userModel = new UsuarioModel();
        $pendentes = $userModel->where('status_usuario', 'pendente')->findAll();
        return $this->respond(['status' => 'success', 'data' => $pendentes], 200);
    }

    // Aprova o lojista e ativa seu perfil
    public function aprovarLojista($id = null)
    {
        $userModel = new UsuarioModel();
        $usuario = $userModel->find($id);

        if (!$usuario) {
            return $this->failNotFound('Usuário não encontrado.');
        }

        $userModel->update($id, ['status_usuario' => 'ativo']);

        return $this->respond([
            'status' => 'success',
            'message' => 'Lojista aprovado e ativado com sucesso.'
        ], 200);
    }

    // Dashboard Estatístico com distribuição percentual por Faixas de Gasto e Filtro Sazonal
    public function index()
    {
        $db = \Config\Database::connect();
        $idEvento = $this->request->getGet('id_evento');

        // Builders Iniciais
        $builderPesquisa = $db->table('pesquisa p');
        $builderOcupacao = $db->table('fluxos_ocupacao f');

        // Se houver um ID de evento passado pelo filtro do Waron, buscamos os limites de data dele
        if ($idEvento) {
            $evento = $db->table('estabelecimento_evento')
                ->where('id_estabelecimento', $idEvento)
                ->where('tipo', 'evento')
                ->get()
                ->getRowArray();

            if ($evento) {
                // Filtra as pesquisas estritamente dentro do período da festa e associadas a ela
                $builderPesquisa->where('p.id_estabelecimento', $idEvento);
                if ($evento['data_inicio']) $builderPesquisa->where('p.respondido_em >=', $evento['data_inicio'] . ' 00:00:00');
                if ($evento['data_fim'])    $builderPesquisa->where('p.respondido_em <=', $evento['data_fim'] . ' 23:59:59');
            }
        } else {
            // Se não houver filtro, o painel exibe apenas os dados dos estabelecimentos do tipo 'fixo'
            $builderPesquisa->join('estabelecimento_evento e', 'e.id_estabelecimento = p.id_estabelecimento')
                ->where('e.tipo', 'fixo');

            $builderOcupacao->join('estabelecimento_evento e', 'e.id_estabelecimento = f.id_estabelecimento')
                ->where('e.tipo', 'fixo');
        }

        // 1. Coleta e Distribuição de Faixas de Gasto (Pizza/Barras no Front)
        $pesquisasClone = clone $builderPesquisa;
        $gastosResult = $pesquisasClone->select('p.faixa_gasto, COUNT(*) as total')
            ->groupBy('p.faixa_gasto')
            ->get()
            ->getResultArray();

        // 2. Média de Satisfação e cálculo puro de NPS
        $npsClone = clone $builderPesquisa;
        $npsData = $npsClone->select('p.nps, p.satisfacao_estrelas')->get()->getResultArray();

        $totalRespostas = count($npsData);
        $promotores = 0;
        $detratores = 0;
        $somaEstrelas = 0;

        foreach ($npsData as $row) {
            $somaEstrelas += $row['satisfacao_estrelas'];
            if ($row['nps'] >= 9) $promotores++;
            if ($row['nps'] <= 6) $detratores++;
        }

        $npsGeral = $totalRespostas > 0 ? (($promotores - $detratores) / $totalRespostas) * 180 : 0;
        $mediaEstrelas = $totalRespostas > 0 ? ($somaEstrelas / $totalRespostas) : 0;

        // 3. Volume total de clientes monitorados (Vem de fluxos_ocupacao se for Macro/Fixo)
        $totalClientes = 0;
        if (!$idEvento) {
            $totalClientes = $builderOcupacao->selectSum('f.volume_clientes', 'total')->get()->getRowArray()['total'] ?? 0;
        } else {
            // Em eventos, o volume de público é medido pelo volume de formulários respondidos passivamente
            $totalClientes = $totalRespostas;
        }

        return $this->respond([
            'status' => 'success',
            'filtros_aplicados' => [
                'id_evento' => $idEvento ?? 'Geral Macro'
            ],
            'metrics' => [
                'total_clientes_detectados' => (int)$totalClientes,
                'media_satisfacao_estrelas'  => round($mediaEstrelas, 2),
                'nps_score'                 => round($npsGeral, 2),
                'distribuicao_gastos'       => $gastosResult
            ]
        ], 200);
    }

    // Exportação de Dados Unificados para o ICMS Turismo
    public function exportarCSV()
    {
        $db = \Config\Database::connect();
        $query = $db->table('pesquisa p')
            ->select('p.id_pesquisa, e.razao_social, p.cidade_origem, p.tempo_permanencia, p.faixa_gasto, p.satisfacao_estrelas, p.respondido_em')
            ->join('estabelecimento_evento e', 'e.id_estabelecimento = p.id_estabelecimento')
            ->get()
            ->getResultArray();

        $filename = "relatorio_icms_turismo_" . date('Ymd') . ".csv";

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");

        $output = fopen("php://output", "w");

        // Bom para o Excel abrir com acentuação correta em PT-BR
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Cabeçalho do CSV
        fputcsv($output, ['ID Pesquisa', 'Local/Evento', 'Cidade Origem', 'Permanência', 'Faixa Gasto', 'Estrelas', 'Data']);

        foreach ($query as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
