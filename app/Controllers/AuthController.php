<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $senha = $this->request->getVar('senha');

        $model = new UsuarioModel();
        $usuario = $model->where('email', $email)->first();

        // 1. Valida se usuário existe
        if (!$usuario) {
            return $this->failUnauthorized('Credenciais inválidas.');
        }

        // 2. Valida se a senha bate com o hash do banco
        if (!password_verify($senha, $usuario['senha'])) {
            return $this->failUnauthorized('Credenciais inválidas.');
        }

        // 3. Valida a regra de negócio do pré-cadastro (Bloqueia se estiver pendente)
        if ($usuario['status'] === 'pendente') {
            return $this->fail('Este cadastro ainda está em análise pela Secretaria de Turismo.', 403);
        }

        if ($usuario['status'] === 'suspenso') {
            return $this->fail('Este usuário está suspenso.', 403);
        }

        // 4. Cria a sessão no servidor
        $sessionData = [
            'id'       => $usuario['id'],
            'nome'     => $usuario['nome_responsavel'],
            'email'    => $usuario['email'],
            'role'     => $usuario['role'],
            'isLogged' => true
        ];
        
        session()->set($sessionData);

        // 5. Retorna a resposta pro Waron redirecionar no Front-end conforme o perfil
        return $this->respond([
            'status' => 'success',
            'message' => 'Login realizado com sucesso',
            'user' => [
                'nome' => $usuario['nome_responsavel'],
                'role' => $usuario['role']
            ]
        ], 200);
    }

    public function logout()
    {
        session()->destroy();
        return $this->respond(['status' => 'success', 'message' => 'Sessão encerrada.'], 200);
    }
}
