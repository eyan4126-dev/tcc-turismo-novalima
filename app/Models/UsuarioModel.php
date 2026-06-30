<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nome_responsavel', 'email', 'senha', 'role', 'status'];
    
    // Timestamps
    protected $useTimestamps    = false; // Banco usa DEFAULT CURRENT_TIMESTAMP

    // Callbacks para hash de senha automático no cadastro
    protected $beforeInsert     = ['hashSenha'];
    protected $beforeUpdate     = ['hashSenha'];

    protected function hashSenha(array $data)
    {
        if (isset($data['data']['senha'])) {
            $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_BCRYPT);
        }
        return $data;
    }
}
