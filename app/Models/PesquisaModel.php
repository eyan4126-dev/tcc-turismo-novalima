<?php

namespace App\Models;

use CodeIgniter\Model;

class PesquisaModel extends Model
{
    protected $table            = 'pesquisas';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_estabelecimento',
        'cidade_origem',
        'tempo_permanencia',
        'local_hospedagem',
        'faixa_gasto',
        'satisfacao_estrelas',
        'nps'
    ];
    protected $useTimestamps    = false; // Banco usa DEFAULT CURRENT_TIMESTAMP na coluna respondido_em
}
