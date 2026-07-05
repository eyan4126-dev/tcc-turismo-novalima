<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function login()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (empty($email) || empty($senha)) {
            session()->setFlashdata('error', 'Por favor, preencha todos os campos.');
            return redirect()->back()->withInput();
        }

        $userModel = new UsuarioModel();

        // Busca o usuário pelo e-mail
        $usuario = $userModel->asArray()->where('email', $email)->first();

        // 1. Valida se o usuário existe
        if (!$usuario) {
            session()->setFlashdata('error', 'Credenciais inválidas ou cadastro pendente de aprovação.');
            session()->setFlashdata('active_tab', 'login');
            return redirect()->back()->withInput();
        }

        // 2. COMPARAÇÃO DIRETA (Sem criptografia)
        if ($senha !== $usuario['senha']) {
            session()->setFlashdata('error', 'Credenciais inválidas ou cadastro pendente de aprovação.');
            session()->setFlashdata('active_tab', 'login');
            return redirect()->back()->withInput();
        }

        // 3. Valida o status do usuário
        if ($usuario['status_usuario'] !== 'ativo') {
            session()->setFlashdata('error', 'Seu cadastro está pendente de aprovação ou suspenso.');
            session()->setFlashdata('active_tab', 'login');
            return redirect()->back()->withInput();
        }

        // 4. Salva os dados limpos na Sessão
        session()->set([
            'id'       => $usuario['id_usuario'],
            'nome'     => $usuario['nome_responsavel'],
            'role'     => $usuario['role_usuario'],
            'isLogged' => true
        ]);

        // 5. Redireciona baseado na role do banco
        if (session()->get('role') === 'admin') {
            return redirect()->to(base_url('admin'));
        } else {
            return redirect()->to(base_url('lojista'));
        }
    }
}
