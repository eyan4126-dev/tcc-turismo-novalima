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

        // Usa password_verify para comparar o hash do banco com a entrada limpa
        if (!password_verify($senha, $usuario['senha'])) {
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

        // 4. Salva os dados na Sessão
        session()->set([
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_responsavel'],
            'role' => $usuario['role_usuario'],
            'isLogged' => true
        ]);

        // 5. Redireciona baseado na role do banco
        if (session()->get('role') === 'admin') {
            return redirect()->to(site_url('admin'));
        } else {
            return redirect()->to(site_url('lojista'));
        }
    }

    public function cadastrarLojista()
    {
        $nome_responsavel = $this->request->getPost('nome_responsavel');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        $razao_social = $this->request->getPost('razao_social');
        $cnpj_mascarado = $this->request->getPost('cnpj');
        $tel_mascarado = $this->request->getPost('telefone');
        $setor = $this->request->getPost('setor');
        $tipo = $this->request->getPost('tipo');

        // Switch de Recompensa (Adesão Opcional à Rede de Descontos)
        $aceita_desconto = $this->request->getPost('aceita_desconto') !== null ? 1 : 0;

        // Limpa as máscaras para salvar apenas números
        $cnpj = preg_replace('/\D/', '', $cnpj_mascarado);
        $telefone = preg_replace('/\D/', '', $tel_mascarado);

        $usuarioModel = new UsuarioModel();
        $estabelecimentoModel = new EstabelecimentoModel();

        if ($usuarioModel->where('email', $email)->first()) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Este e-mail já está cadastrado.');
        }

        if ($estabelecimentoModel->where('cnpj', $cnpj)->first()) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Este CNPJ já está cadastrado.');
        }

        $dadosUsuario = [
            'nome_responsavel' => $nome_responsavel,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'role_usuario' => 'lojista',
            'status_usuario' => 'pendente'
        ];

        $id_usuario = $usuarioModel->insert($dadosUsuario);

        if (!$id_usuario) {
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Erro ao criar conta de usuário.');
        }

        $dadosEstabelecimento = [
            'id_usuario' => $id_usuario,
            'razao_social' => $razao_social,
            'cnpj' => $cnpj,
            'telefone' => $telefone,
            'setor' => $setor,
            'tipo' => $tipo,
            'aceita_desconto' => $aceita_desconto,
            'desconto_percentagem' => 10, // Valor padrão de 10% na adesão
            'pin_validacao' => null
        ];

        if ($estabelecimentoModel->insert($dadosEstabelecimento)) {
            session()->setFlashdata('active_tab', 'login');
            return redirect()->back()->with('success', 'Pré-cadastro realizado com sucesso! Aguarde a aprovação do administrador.');
        } else {
            $usuarioModel->delete($id_usuario);
            session()->setFlashdata('active_tab', 'cadastro');
            return redirect()->back()->withInput()->with('error', 'Erro ao salvar os dados do estabelecimento.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Sessão encerrada com sucesso.');
    }

    // ====================================================================
    // MIGRATION SCRIPT: Injeta a coluna 'desconto_percentagem' na Railway
    // ====================================================================
    public function executarMigracaoDesconTour()
    {
        $db = \Config\Database::connect();

        try {
            // Adiciona a coluna para flexibilizar a porcentagem de desconto do lojista
            $query = "ALTER TABLE `estabelecimento_evento` 
                      ADD COLUMN `desconto_percentagem` INT NOT NULL DEFAULT 10 AFTER `aceita_desconto`";

            $db->query($query);

            return "<h3>Sucesso! 🎟️</h3><p>A coluna 'desconto_percentagem' para o Programa DesconTour foi criada com sucesso na Railway!</p><p><a href='" . site_url('lojista/dashboard') . "'>Voltar ao Painel do Lojista</a></p>";
        } catch (\Exception $e) {
            // Se a coluna já existir, ignora o erro silenciosamente
            if (strpos($e->getMessage(), '1060') !== false) {
                return "<h3>Tudo OK!</h3><p>O seu banco de dados já possui suporte dinâmico ao Programa DesconTour!</p><p><a href='" . site_url('lojista/dashboard') . "'>Acessar o Painel</a></p>";
            }
            return "<h3>Erro ao atualizar tabela:</h3><p style='color:red;'>" . $e->getMessage() . "</p>";
        }
    }
}