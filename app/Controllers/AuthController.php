<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\EstabelecimentoModel;

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

        // 2. COMPARAÇÃO DIRETA (Sem criptografia - Conforme escopo MVP)
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
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_responsavel'],
            'role' => $usuario['role_usuario'], // Define como 'role'
            'isLogged' => true
        ]);

        // 5. Redireciona baseado na role do banco
        if (session()->get('role') === 'admin') {
            return redirect()->to(base_url('admin'));
        } else {
            return redirect()->to(base_url('lojista'));
        }
    }

    public function cadastrarLojista()
    {
        // 1. Recebe os dados do formulário
        $nome_responsavel = $this->request->getPost('nome_responsavel');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        $razao_social = $this->request->getPost('razao_social');
        $cnpj_mascarado = $this->request->getPost('cnpj');
        $tel_mascarado = $this->request->getPost('telefone');
        $setor = $this->request->getPost('setor');
        $tipo = $this->request->getPost('tipo'); // 'fixo' ou 'evento'

        // Limpa as máscaras para salvar apenas números
        $cnpj = preg_replace('/\D/', '', $cnpj_mascarado);
        $telefone = preg_replace('/\D/', '', $tel_mascarado);

        // 2. Instancia os Models
        $usuarioModel = new UsuarioModel();
        $estabelecimentoModel = new EstabelecimentoModel();

        // 3. Validações de duplicidade nas tabelas corretas
        if ($usuarioModel->where('email', $email)->first()) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Este e-mail já está cadastrado.');
        }

        if ($estabelecimentoModel->where('cnpj', $cnpj)->first()) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Este CNPJ já está cadastrado.');
        }

        // --- PASSO 1: Salvar o Usuário ---
        $dadosUsuario = [
            'nome_responsavel' => $nome_responsavel,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'role_usuario' => 'lojista',
            'status_usuario' => 'pendente'
        ];

        // Insere o usuário e pega o ID gerado automaticamente (AI)
        $id_usuario = $usuarioModel->insert($dadosUsuario);

        if (!$id_usuario) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Erro ao criar conta de usuário.');
        }

        // --- PASSO 2: Salvar o Estabelecimento vinculado ao Usuário ---
        $dadosEstabelecimento = [
            'id_usuario' => $id_usuario, // Chave estrangeira ligando ao usuário criado acima
            'razao_social' => $razao_social,
            'cnpj' => $cnpj,
            'telefone' => $telefone,
            'setor' => $setor,
            'tipo' => $tipo,
            // 'token_qr_code', 'data_inicio', 'data_fim' preencher se necessário, ou deixar nulo se o banco permitir
        ];

        if ($estabelecimentoModel->insert($dadosEstabelecimento)) {
            session()->setFlashdata('active_tab', 'login');
            return redirect()->back()->with('success', 'Pré-cadastro realizado com sucesso! Aguarde a aprovação do administrador.');
        } else {
            // Caso falhe o estabelecimento, removemos o usuário para não deixar dados órfãos
            $usuarioModel->delete($id_usuario);

            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Erro ao salvar os dados do estabelecimento.');
        }
    }

    // 6. MÉTODO DE LOGOUT ADICIONADO
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Sessão encerrada com sucesso.');
    }
}