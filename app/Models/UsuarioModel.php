<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'id_usuario';
    protected $allowedFields    = ['nome_responsavel', 'email', 'senha', 'role_usuario', 'status_usuario'];
    protected $useTimestamps    = false;
}
