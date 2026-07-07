<?php

namespace App\Models;

use CodeIgniter\Model;

class PesquisaModel extends Model
{
    protected $table = 'pesquisa';
    protected $primaryKey = 'id_pesquisa';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'id_estabelecimento',
        'cidade_origem',
        'tempo_permanencia',
        'local_hospedagem',
        'faixa_gasto',
        'satisfacao_estrelas',
        'nps',
        'motivo_visita'
    ];

    protected bool $allowEmptyInserts = false;

    protected $validationRules = [
        'id_estabelecimento' => 'required|integer',
        'cidade_origem' => 'required|min_length[3]|max_length[100]',
        'tempo_permanencia' => 'required|in_list[bate_volta,dormir]',
        'local_hospedagem' => 'permit_empty|in_list[hotel_pousada,airbnb_aluguel,casa_amigos_parentes,outro]',
        'faixa_gasto' => 'permit_empty',
        'valor_gasto_estimado' => 'required',
        'satisfacao_estrelas' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'nps' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]',
        'motivo_visita' => 'required|in_list[lazer,negocios,parentes_amigos,outro]'
    ];

    protected $validationMessages = [
        'cidade_origem' => ['required' => 'A cidade de origem do turista é obrigatória.'],
        'motivo_visita' => ['required' => 'O motivo da visita precisa ser selecionado.', 'in_list' => 'Opção inválida.'],
        'valor_gasto_estimado' => ['required' => 'O planejamento financeiro/gasto médio precisa ser informado.']
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getDadosFiscaisPorPeriodo($dataInicio, $dataFim)
    {
        return $this->where('created_at >=', $dataInicio . ' 00:00:00')
            ->where('created_at <=', $dataFim . ' 23:59:59')
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    public function getIndicadoresConsolidados()
    {
        $db = \Config\Database::connect();

        $geral = $db->table($this->table)
            ->select('COUNT(id_pesquisa) as total_respostas, AVG(faixa_gasto) as gasto_medio_geral, AVG(satisfacao_estrelas) as satisfacao_media')
            ->get()->getRowArray();

        $totalNps = $db->table($this->table)->countAllResults();
        if ($totalNps > 0) {
            $promotores = $db->table($this->table)->where('nps >=', 9)->countAllResults();
            $detratores = $db->table($this->table)->where('nps <=', 6)->countAllResults();
            $npsConsolidado = (($promotores - $detratores) / $totalNps) * 100;
        } else {
            $npsConsolidado = 0;
        }

        return [
            'total_respostas' => (int) ($geral['total_respostas'] ?? 0),
            'gasto_medio' => (float) ($geral['gasto_medio_geral'] ?? 0.00),
            'satisfacao_media' => round((float) ($geral['satisfacao_media'] ?? 0.0), 1),
            'nps' => round($npsConsolidado, 1)
        ];
    }

    public function getCruzamentoPorMotivo()
    {
        return $this->select('motivo_visita, COUNT(id_pesquisa) as quantidade, AVG(faixa_gasto) as gasto_medio, AVG(satisfacao_estrelas) as satisfacao_media')->groupBy('motivo_visita')->orderBy('gasto_medio', 'DESC')->findAll();
    }

    public function getCruzamentoPorPermanencia()
    {
        return $this->select('tempo_permanencia, COUNT(id_pesquisa) as quantidade, AVG(faixa_gasto) as gasto_medio, AVG(nps) as nps_medio')->groupBy('tempo_permanencia')->findAll();
    }
}