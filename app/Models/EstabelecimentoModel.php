<?php

namespace App\Models;

use CodeIgniter\Model;

class EstabelecimentoModel extends Model
{
    protected $table = 'estabelecimento_evento';
    protected $primaryKey = 'id_estabelecimento';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    // CRUCIAL: O 'desconto_percentagem' e 'foto' precisam estar aqui,
    // senão o CodeIgniter ignora silenciosamente o update deles!
    protected $allowedFields = [
        'id_usuario',
        'razao_social',
        'cnpj',
        'telefone',
        'setor',
        'token_qr_code',
        'tipo',
        'data_inicio',
        'data_fim',
        'aceita_desconto',
        'desconto_percentagem', // Adicionado para permitir atualização fluida
        'pin_validacao',
        'foto'
    ];

    protected $useTimestamps = false;
}