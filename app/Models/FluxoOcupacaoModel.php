<?php

namespace App\Models;

use CodeIgniter\Model;

class FluxoOcupacaoModel extends Model
{
    protected $table            = 'fluxos_ocupacao';
    protected $primaryKey       = 'id_fluxos';
    protected $allowedFields    = [
        'id_estabelecimento',
        'volume_clientes',
        'quartos_ocupados',
        'capacidade_maxima_quartos',
        'data_referencia'
    ];
}
