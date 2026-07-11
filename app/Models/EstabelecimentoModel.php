<?php

namespace App\Models;

use CodeIgniter\Model;

class EstabelecimentoModel extends Model
{
    protected $table = 'estabelecimento_evento';
    protected $primaryKey = 'id_estabelecimento';
    protected $useTimestamps = false;
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
        'aceita_desconto', // NOVO CAMPO: Indica se o lojista aceitou participar da rede de descontos (0 ou 1)
        'pin_validacao'    // NOVO CAMPO: PIN numérico de 4 dígitos gerado na aprovação para validação de balcão
    ];
}