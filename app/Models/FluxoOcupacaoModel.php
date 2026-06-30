<?php

namespace App\Models;

use CodeIgniter\Model;

class FluxoOcupacaoModel extends Model
{
    protected $table            = 'fluxos_ocupacao';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_estabelecimento', 'volume_clientes', 'quartos_ocupados', 'capacidade_maxima_quartos', 'data_referencia'];
    protected $useTimestamps    = false;
}
