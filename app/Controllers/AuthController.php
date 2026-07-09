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

        // CORREÇÃO AQUI: Usa password_verify para comparar o hash do banco com a entrada limpa
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

        // 4. Salva os dados limpos na Sessão
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
        return redirect()->to(site_url('login'))->with('success', 'Sessão encerrada com sucesso.');
    }

    public function semearBanco()
    {
        $db = \Config\Database::connect();

        // 1. LIMPEZA TOTAL PREVENTIVA (Evita erros de duplicidade e limpa dados antigos)
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $db->table('pesquisa')->truncate();
        $db->table('fluxos_ocupacao')->truncate();
        $db->table('estabelecimento_evento')->truncate();
        $db->table('usuario')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 2. DADOS DA TABELA: usuario (Com hash Bcrypt gerado dinamicamente)
        $usuarios = [
            [
                'id_usuario' => 1,
                'nome_responsavel' => 'Mariana Silva (Secult)',
                'email' => 'admin@novalima.mg.gov.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'admin',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-05-10 09:00:00'
            ],
            [
                'id_usuario' => 2,
                'nome_responsavel' => 'Roberto Vasconcelos',
                'email' => 'pousada.macacos@gmail.com',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-01 10:15:00'
            ],
            [
                'id_usuario' => 3,
                'nome_responsavel' => 'Juliana Gontijo',
                'email' => 'contato@veredaartesanal.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-02 14:30:00'
            ],
            [
                'id_usuario' => 4,
                'nome_responsavel' => 'Carlos Alberto Santos',
                'email' => 'gerencia@hotelalphaville.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-03 08:45:00'
            ],
            [
                'id_usuario' => 5,
                'nome_responsavel' => 'Beatriz Souza',
                'email' => 'reservas@gandarealodge.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-04 11:20:00'
            ],
            [
                'id_usuario' => 6,
                'nome_responsavel' => 'Felipe Drummond',
                'email' => 'festival@cervejariacanada.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-15 16:00:00'
            ],
            [
                'id_usuario' => 7,
                'nome_responsavel' => 'Aline Ferreira (Pendente)',
                'email' => 'teste.lojista@outlook.com',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'pendente',
                'criado_em' => '2026-07-08 11:00:00'
            ]
        ];

        foreach ($usuarios as $u) {
            $db->table('usuario')->insert($u);
        }

        // 3. DADOS DA TABELA: estabelecimento_evento
        $estabelecimentos = [
            [
                'id_estabelecimento' => 1,
                'id_usuario' => 2,
                'razao_social' => 'Pousada do Sol de Macacos Ltda',
                'cnpj' => '12.345.678/0001-90',
                'telefone' => '(31) 98888-1111',
                'setor' => 'hospedagem',
                'token_qr_code' => '8f7c9e1a2b3c4d5e6f7a',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null
            ],
            [
                'id_estabelecimento' => 2,
                'id_usuario' => 3,
                'razao_social' => 'Restaurante e Cervejaria Vereda Artesanal (Jardim Canadá)',
                'cnpj' => '98.765.432/0001-10',
                'telefone' => '(31) 98777-2222',
                'setor' => 'alimentacao_comercio',
                'token_qr_code' => '1a2b3c4d5e6f7a8b9c0d',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null
            ],
            [
                'id_estabelecimento' => 3,
                'id_usuario' => 4,
                'razao_social' => 'Hotel Lagoa dos Ingleses Executive',
                'cnpj' => '45.678.901/0001-22',
                'telefone' => '(31) 3581-3000',
                'setor' => 'hospedagem',
                'token_qr_code' => 'a1b2c3d4e5f6g7h8i9j0',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null
            ],
            [
                'id_estabelecimento' => 4,
                'id_usuario' => 5,
                'razao_social' => 'Gandarela Eco Lodge e Aventuras',
                'cnpj' => '23.456.789/0001-55',
                'telefone' => '(31) 99111-3333',
                'setor' => 'natural',
                'token_qr_code' => '0j9i8h7g6f5e4d3c2b1a',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null
            ],
            [
                'id_estabelecimento' => 5,
                'id_usuario' => 6,
                'razao_social' => 'Festival Gastronômico e de Cerveja Artesanal de Nova Lima',
                'cnpj' => '34.567.890/0001-44',
                'telefone' => '(31) 99222-4444',
                'setor' => 'cultural',
                'token_qr_code' => 'bc1de2fg3hi4jk5lm6no',
                'tipo' => 'evento',
                'data_inicio' => '2026-07-03',
                'data_fim' => '2026-07-05'
            ]
        ];

        foreach ($estabelecimentos as $e) {
            $db->table('estabelecimento_evento')->insert($e);
        }

        // 4. DADOS DA TABELA: fluxos_ocupacao
        $fluxos = [
            [
                'id_fluxos' => 1,
                'id_estabelecimento' => 1,
                'volume_clientes' => 45,
                'quartos_ocupados' => 12,
                'capacidade_maxima_quartos' => 15,
                'data_referencia' => '2026-06-27',
                'criado_em' => '2026-06-27 22:00:00'
            ],
            [
                'id_fluxos' => 2,
                'id_estabelecimento' => 1,
                'volume_clientes' => 55,
                'quartos_ocupados' => 14,
                'capacidade_maxima_quartos' => 15,
                'data_referencia' => '2026-07-04',
                'criado_em' => '2026-07-04 23:00:00'
            ],
            [
                'id_fluxos' => 3,
                'id_estabelecimento' => 3,
                'volume_clientes' => 120,
                'quartos_ocupados' => 38,
                'capacidade_maxima_quartos' => 50,
                'data_referencia' => '2026-06-30',
                'criado_em' => '2026-06-30 20:00:00'
            ],
            [
                'id_fluxos' => 4,
                'id_estabelecimento' => 3,
                'volume_clientes' => 160,
                'quartos_ocupados' => 47,
                'capacidade_maxima_quartos' => 50,
                'data_referencia' => '2026-07-04',
                'criado_em' => '2026-07-04 21:30:00'
            ]
        ];

        foreach ($fluxos as $f) {
            $db->table('fluxos_ocupacao')->insert($f);
        }

        // 5. DADOS DA TABELA: pesquisa
        $pesquisas = [
            ['id_pesquisa' => 1, 'id_estabelecimento' => 1, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'hotel_pousada', 'valor_gasto_estimado' => 450.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-06-27 14:20:00'],
            ['id_pesquisa' => 2, 'id_estabelecimento' => 1, 'cidade_origem' => 'Contagem - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 180.00, 'satisfacao_estrelas' => 4, 'nps' => 8, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-06-28 11:15:00'],
            ['id_pesquisa' => 3, 'id_estabelecimento' => 1, 'cidade_origem' => 'Rio de Janeiro - RJ', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'hotel_pousada', 'valor_gasto_estimado' => 850.00, 'satisfacao_estrelas' => 5, 'nps' => 9, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-04 18:30:00'],
            ['id_pesquisa' => 4, 'id_estabelecimento' => 2, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 220.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-06-26 21:00:00'],
            ['id_pesquisa' => 5, 'id_estabelecimento' => 2, 'cidade_origem' => 'Nova Lima - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 110.00, 'satisfacao_estrelas' => 4, 'nps' => 7, 'motivo_visita' => 'outro', 'respondido_em' => '2026-06-27 19:45:00'],
            ['id_pesquisa' => 6, 'id_estabelecimento' => 2, 'cidade_origem' => 'São Paulo - SP', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'airbnb_aluguel', 'valor_gasto_estimado' => 350.00, 'satisfacao_estrelas' => 5, 'nps' => 9, 'motivo_visita' => 'negocios', 'respondido_em' => '2026-07-02 22:10:00'],
            ['id_pesquisa' => 7, 'id_estabelecimento' => 3, 'cidade_origem' => 'São Paulo - SP', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'hotel_pousada', 'valor_gasto_estimado' => 1200.00, 'satisfacao_estrelas' => 4, 'nps' => 8, 'motivo_visita' => 'negocios', 'respondido_em' => '2026-06-29 08:30:00'],
            ['id_pesquisa' => 8, 'id_estabelecimento' => 3, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 150.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-04 10:00:00'],
            ['id_pesquisa' => 9, 'id_estabelecimento' => 3, 'cidade_origem' => 'Betim - MG', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'hotel_pousada', 'valor_gasto_estimado' => 500.00, 'satisfacao_estrelas' => 3, 'nps' => 6, 'motivo_visita' => 'parentes_amigos', 'respondido_em' => '2026-07-05 11:15:00'],
            ['id_pesquisa' => 10, 'id_estabelecimento' => 4, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 90.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-06-28 16:00:00'],
            ['id_pesquisa' => 11, 'id_estabelecimento' => 4, 'cidade_origem' => 'Rio de Janeiro - RJ', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'casa_amigos_parentes', 'valor_gasto_estimado' => 300.00, 'satisfacao_estrelas' => 4, 'nps' => 9, 'motivo_visita' => 'parentes_amigos', 'respondido_em' => '2026-07-01 14:45:00'],
            ['id_pesquisa' => 12, 'id_estabelecimento' => 5, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 250.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-03 20:15:00'],
            ['id_pesquisa' => 13, 'id_estabelecimento' => 5, 'cidade_origem' => 'Contagem - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 190.00, 'satisfacao_estrelas' => 4, 'nps' => 9, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-03 22:40:00'],
            ['id_pesquisa' => 14, 'id_estabelecimento' => 5, 'cidade_origem' => 'Divinópolis - MG', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'airbnb_aluguel', 'valor_gasto_estimado' => 620.00, 'satisfacao_estrelas' => 5, 'nps' => 10, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-04 15:30:00'],
            ['id_pesquisa' => 15, 'id_estabelecimento' => 5, 'cidade_origem' => 'Juiz de Fora - MG', 'tempo_permanencia' => 'dormir', 'local_hospedagem' => 'hotel_pousada', 'valor_gasto_estimado' => 780.00, 'satisfacao_estrelas' => 4, 'nps' => 8, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-04 19:10:00'],
            ['id_pesquisa' => 16, 'id_estabelecimento' => 5, 'cidade_origem' => 'Belo Horizonte - MG', 'tempo_permanencia' => 'bate_volta', 'local_hospedagem' => null, 'valor_gasto_estimado' => 130.00, 'satisfacao_estrelas' => 2, 'nps' => 4, 'motivo_visita' => 'lazer', 'respondido_em' => '2026-07-05 13:00:00']
        ];

        foreach ($pesquisas as $p) {
            $db->table('pesquisa')->insert($p);
        }

        return "Banco de dados limpo e semeado por completo com sucesso!";
    }
}