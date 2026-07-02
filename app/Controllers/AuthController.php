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

        if (!$usuario) {
            return $this->failUnauthorized('Credenciais inválidas.');
        }

        if (!password_verify($senha, $usuario['senha'])) {
            return $this->failUnauthorized('Credenciais inválidas.');
        }

        if ($usuario['status_usuario'] === 'pendente') {
            return $this->fail('Este cadastro ainda está em análise pela Secretaria de Turismo.', 403);
        }

        if ($usuario['status_usuario'] === 'suspenso') {
            return $this->fail('Este usuário está suspenso.', 403);
        }

        $sessionData = [
            'id_usuario'   => $usuario['id_usuario'],
            'nome'         => $usuario['nome_responsavel'],
            'email'        => $usuario['email'],
            'role_usuario' => $usuario['role_usuario'],
            'isLogged'     => true
        ];

        session()->set($sessionData);

        return $this->respond([
            'status' => 'success',
            'message' => 'Login realizado com sucesso',
            'user' => [
                'nome' => $usuario['nome_responsavel'],
                'role' => $usuario['role_usuario']
            ]
        ], 200);
    }

    public function registrarPendente()
    {
        $rules = [
            'nome_responsavel' => 'required|min_length[3]|max_length[150]',
            'email'            => 'required|valid_email|is_unique[usuario.email]',
            'senha'            => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new UsuarioModel();
        $model->insert([
            'nome_responsavel' => strip_tags(trim($this->request->getVar('nome_responsavel'))),
            'email'            => trim($this->request->getVar('email')),
            'senha'            => password_hash($this->request->getVar('senha'), PASSWORD_DEFAULT),
            'role_usuario'     => 'lojista',
            'status_usuario'   => 'pendente'
        ]);

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Solicitação enviada. Seu cadastro está na fila de aprovação da prefeitura.'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return $this->respond(['status' => 'success', 'message' => 'Sessão encerrada.'], 200);
    }
}
