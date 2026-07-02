<?php

namespace App\Models;

use CodeIgniter\Model;

class PesquisaModel extends Model
{
    protected $table            = 'pesquisa';
    protected $primaryKey       = 'id_pesquisa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // IMPORTANTÍSSIMO: Liberado o campo novo para permitir o INSERT do CodeIgniter
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

    // Regras de validação do Back-end atualizadas para o TCC
    protected $validationRules = [
        'id_estabelecimento'  => 'required|integer',
        'cidade_origem'       => 'required|min_length[3]|max_length[100]',
        'tempo_permanencia'   => 'required|in_list[bate_volta,dormir]',
        'local_hospedagem'    => 'permit_empty|in_list[hotel_pousada,airbnb_aluguel,casa_amigos_parentes,outro]',
        'faixa_gasto'         => 'required|in_list[0_50,51_100,101_200,201_plus]',
        'satisfacao_estrelas' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'nps'                 => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]',
        'motivo_visita'       => 'required|in_list[lazer,negocios,parentes_amigos,outro]'
    ];

    protected $validationMessages = [
        'cidade_origem' => [
            'required' => 'A cidade de origem do turista é obrigatória.',
        ],
        'motivo_visita' => [
            'required' => 'O motivo da visita precisa ser selecionado.',
            'in_list'  => 'Opção de motivo de visita inválida.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
