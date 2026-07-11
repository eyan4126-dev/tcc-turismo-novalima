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

        // NOVO PARÂMETRO: Switch de Recompensa (Adesão Opcional à Rede de Descontos)
        $aceita_desconto = $this->request->getPost('aceita_desconto') !== null ? 1 : 0;

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

        // Insere o usuário e pega o ID gerado automaticamente
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
            'aceita_desconto' => $aceita_desconto, // NOVO CAMPO: Salva adesão do parceiro à rede de vantagens
            'pin_validacao' => null                // Fica nulo, será gerado pelo Admin na aprovação
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Sessão encerrada com sucesso.');
    }

    public function semearBanco()
    {
        $db = \Config\Database::connect();

        // 1. LIMPEZA TOTAL PREVENTIVA
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $db->table('pesquisa')->truncate();
        $db->table('fluxos_ocupacao')->truncate();
        $db->table('estabelecimento_evento')->truncate();
        $db->table('usuario')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 2. DADOS DA TABELA: usuario (Com adição de senhas reais criptografadas)
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
                'nome_responsavel' => 'Bernardo Guimarães (Comandante)',
                'email' => 'reserva@valedocomandante.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-01 10:00:00'
            ],
            [
                'id_usuario' => 3,
                'nome_responsavel' => 'Gustavo Krug (Krug Bier)',
                'email' => 'contato@krug.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-02 11:30:00'
            ],
            [
                'id_usuario' => 4,
                'nome_responsavel' => 'Adriana Costa (eSuites)',
                'email' => 'gerencia@esuiteslagoabr.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-03 08:20:00'
            ],
            [
                'id_usuario' => 5,
                'nome_responsavel' => 'Rodrigo Macacos (Pousada)',
                'email' => 'contato@pousadadorodrigo.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-04 14:15:00'
            ],
            [
                'id_usuario' => 6,
                'nome_responsavel' => 'Felipe Verace (Cervejaria)',
                'email' => 'comercial@cervejariaverace.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-05 09:10:00'
            ],
            [
                'id_usuario' => 7,
                'nome_responsavel' => 'Amanda Rola-Moça (Guia Local)',
                'email' => 'amanda.ecoturismo@gmail.com',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-06 13:40:00'
            ],
            [
                'id_usuario' => 8,
                'nome_responsavel' => 'Associação de Lojistas de Macacos',
                'email' => 'comercial@festivaisdemacacos.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-15 16:00:00'
            ],
            [
                'id_usuario' => 9,
                'nome_responsavel' => 'Coordenador Uaiktoberfest',
                'email' => 'producao@uaiktoberfest.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-18 10:00:00'
            ],
            [
                'id_usuario' => 10,
                'nome_responsavel' => 'Comissão Organizadora Festa do Cavalo',
                'email' => 'contato@festadocavalor.com.br',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'ativo',
                'criado_em' => '2026-06-20 08:00:00'
            ],
            [
                'id_usuario' => 11,
                'nome_responsavel' => 'Clara Albuquerque (Eco-Hostel)',
                'email' => 'clara.hostelmacacos@outlook.com',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'role_usuario' => 'lojista',
                'status_usuario' => 'pendente',
                'criado_em' => '2026-07-08 11:00:00'
            ]
        ];

        foreach ($usuarios as $u) {
            $db->table('usuario')->insert($u);
        }

        // 3. DADOS DA TABELA: estabelecimento_evento (Atualizados com os campos aceita_desconto e pin_validacao de fábrica)
        $estabelecimentos = [
            [
                'id_estabelecimento' => 1,
                'id_usuario' => 2,
                'razao_social' => 'Pousada Vale do Comandante (Macacos)',
                'cnpj' => '12.345.678/0001-90',
                'telefone' => '(31) 3547-7500',
                'setor' => 'hospedagem',
                'token_qr_code' => '8f7c9e1a2b3c4d5e6f7a',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 1,
                'pin_validacao' => '4824'
            ],
            [
                'id_estabelecimento' => 2,
                'id_usuario' => 3,
                'razao_social' => 'Cervejaria Krug Bier (Jardim Canadá)',
                'cnpj' => '98.765.432/0001-10',
                'telefone' => '(31) 3507-0777',
                'setor' => 'alimentacao_comercio',
                'token_qr_code' => '1a2b3c4d5e6f7a8b9c0d',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 1,
                'pin_validacao' => '1099'
            ],
            [
                'id_estabelecimento' => 3,
                'id_usuario' => 4,
                'razao_social' => 'eSuites Spa Lagoa dos Ingleses (Alphaville)',
                'cnpj' => '45.678.901/0001-22',
                'telefone' => '(31) 3581-3000',
                'setor' => 'hospedagem',
                'token_qr_code' => 'a1b2c3d4e5f6g7h8i9j0',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 1,
                'pin_validacao' => '2580'
            ],
            [
                'id_estabelecimento' => 4,
                'id_usuario' => 5,
                'razao_social' => 'Pousada do Rodrigo (Macacos)',
                'cnpj' => '23.456.789/0001-55',
                'telefone' => '(31) 98845-6232',
                'setor' => 'hospedagem',
                'token_qr_code' => '0j9i8h7g6f5e4d3c2b1a',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ],
            [
                'id_estabelecimento' => 5,
                'id_usuario' => 6,
                'razao_social' => 'Cervejaria Verace (Jardim Canadá)',
                'cnpj' => '54.321.098/0001-44',
                'telefone' => '(31) 3541-6103',
                'setor' => 'alimentacao_comercio',
                'token_qr_code' => '7b6f5e4d3c2b1a0j9i8h',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 1,
                'pin_validacao' => '5543'
            ],
            [
                'id_estabelecimento' => 6,
                'id_usuario' => 7,
                'razao_social' => 'Bicame de Pedra (Trilha Histórica)',
                'cnpj' => '11.222.333/0001-44',
                'telefone' => '(31) 3541-4334',
                'setor' => 'natural',
                'token_qr_code' => 'bc1de2fg3hi4jk5lm6no',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ],
            [
                'id_estabelecimento' => 7,
                'id_usuario' => 7,
                'razao_social' => 'Parque Natural Rego dos Carrapatos',
                'cnpj' => '11.222.333/0001-55',
                'telefone' => '(31) 3541-4334',
                'setor' => 'natural',
                'token_qr_code' => 'z9y8x7w6v5u4t3s2r1qp',
                'tipo' => 'fixo',
                'data_inicio' => null,
                'data_fim' => null,
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ],
            [
                'id_estabelecimento' => 8,
                'id_usuario' => 8,
                'razao_social' => 'Festival de Gastronomia de Macacos 2026',
                'cnpj' => '44.555.666/0001-77',
                'telefone' => '(31) 98877-3333',
                'setor' => 'cultural',
                'token_qr_code' => 'evt_macacos_2026_xyz',
                'tipo' => 'evento',
                'data_inicio' => '2026-07-10',
                'data_fim' => '2026-07-12',
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ],
            [
                'id_estabelecimento' => 9,
                'id_usuario' => 9,
                'razao_social' => 'Festival Uaiktoberfest de Nova Lima',
                'cnpj' => '88.999.000/0001-88',
                'telefone' => '(31) 99122-4444',
                'setor' => 'cultural',
                'token_qr_code' => 'evt_uaikt_2026_abc',
                'tipo' => 'evento',
                'data_inicio' => '2026-10-15',
                'data_fim' => '2026-10-18',
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ],
            [
                'id_estabelecimento' => 10,
                'id_usuario' => 10,
                'razao_social' => 'Festa do Cavalo de Nova Lima 2026',
                'cnpj' => '77.888.999/0001-11',
                'telefone' => '(31) 3541-4334',
                'setor' => 'cultural',
                'token_qr_code' => 'evt_cavalo_2026_abc',
                'tipo' => 'evento',
                'data_inicio' => '2026-07-15',
                'data_fim' => '2026-07-19',
                'aceita_desconto' => 0,
                'pin_validacao' => null
            ]
        ];

        foreach ($estabelecimentos as $e) {
            $db->table('estabelecimento_evento')->insert($e);
        }

        // 4. DADOS DA TABELA: fluxos_ocupacao (Preservado)
        $fluxos = [
            ['id_fluxos' => 1, 'id_estabelecimento' => 1, 'volume_clientes' => 40, 'quartos_ocupados' => 12, 'capacidade_maxima_quartos' => 18, 'data_referencia' => '2026-06-13', 'criado_em' => '2026-06-13 22:00:00'],
            ['id_fluxos' => 2, 'id_estabelecimento' => 1, 'volume_clientes' => 52, 'quartos_ocupados' => 17, 'capacidade_maxima_quartos' => 18, 'data_referencia' => '2026-06-20', 'criado_em' => '2026-06-20 22:00:00'],
            ['id_fluxos' => 3, 'id_estabelecimento' => 1, 'volume_clientes' => 56, 'quartos_ocupados' => 18, 'capacidade_maxima_quartos' => 18, 'data_referencia' => '2026-06-27', 'criado_em' => '2026-06-27 22:00:00'],
            ['id_fluxos' => 4, 'id_estabelecimento' => 1, 'volume_clientes' => 48, 'quartos_ocupados' => 15, 'capacidade_maxima_quartos' => 18, 'data_referencia' => '2026-07-04', 'criado_em' => '2026-07-04 23:00:00'],

            ['id_fluxos' => 5, 'id_estabelecimento' => 3, 'volume_clientes' => 140, 'quartos_ocupados' => 55, 'capacidade_maxima_quartos' => 120, 'data_referencia' => '2026-06-15', 'criado_em' => '2026-06-15 20:00:00'],
            ['id_fluxos' => 6, 'id_estabelecimento' => 3, 'volume_clientes' => 220, 'quartos_ocupados' => 89, 'capacidade_maxima_quartos' => 120, 'data_referencia' => '2026-06-22', 'criado_em' => '2026-06-22 20:00:00'],
            ['id_fluxos' => 7, 'id_estabelecimento' => 3, 'volume_clientes' => 280, 'quartos_ocupados' => 112, 'capacidade_maxima_quartos' => 120, 'data_referencia' => '2026-06-29', 'criado_em' => '2026-06-29 20:00:00'],
            ['id_fluxos' => 8, 'id_estabelecimento' => 3, 'volume_clientes' => 310, 'quartos_ocupados' => 118, 'capacidade_maxima_quartos' => 120, 'data_referencia' => '2026-07-04', 'criado_em' => '2026-07-04 21:30:00'],

            ['id_fluxos' => 9, 'id_estabelecimento' => 4, 'volume_clientes' => 30, 'quartos_ocupados' => 10, 'capacidade_maxima_quartos' => 22, 'data_referencia' => '2026-06-14', 'criado_em' => '2026-06-14 20:00:00'],
            ['id_fluxos' => 10, 'id_estabelecimento' => 4, 'volume_clientes' => 54, 'quartos_ocupados' => 20, 'capacidade_maxima_quartos' => 22, 'data_referencia' => '2026-06-28', 'criado_em' => '2026-06-28 20:00:00'],
            ['id_fluxos' => 11, 'id_estabelecimento' => 4, 'volume_clientes' => 61, 'quartos_ocupados' => 22, 'capacidade_maxima_quartos' => 22, 'data_referencia' => '2026-07-05', 'criado_em' => '2026-07-05 20:00:00'],

            ['id_fluxos' => 12, 'id_estabelecimento' => 2, 'volume_clientes' => 450, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-06-20', 'criado_em' => '2026-06-20 23:59:00'],
            ['id_fluxos' => 13, 'id_estabelecimento' => 2, 'volume_clientes' => 520, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-06-27', 'criado_em' => '2026-06-27 23:59:00'],
            ['id_fluxos' => 14, 'id_estabelecimento' => 5, 'volume_clientes' => 380, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-06-27', 'criado_em' => '2026-06-27 23:59:00'],

            ['id_fluxos' => 15, 'id_estabelecimento' => 8, 'volume_clientes' => 1500, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-10', 'criado_em' => '2026-07-10 23:00:00'],
            ['id_fluxos' => 16, 'id_estabelecimento' => 8, 'volume_clientes' => 2800, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-11', 'criado_em' => '2026-07-11 23:00:00'],
            ['id_fluxos' => 17, 'id_estabelecimento' => 8, 'volume_clientes' => 1900, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-12', 'criado_em' => '2026-07-12 21:00:00'],

            ['id_fluxos' => 18, 'id_estabelecimento' => 10, 'volume_clientes' => 2500, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-15', 'criado_em' => '2026-07-15 22:00:00'],
            ['id_fluxos' => 19, 'id_estabelecimento' => 10, 'volume_clientes' => 4800, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-18', 'criado_em' => '2026-07-18 23:00:00'],
            ['id_fluxos' => 20, 'id_estabelecimento' => 10, 'volume_clientes' => 3100, 'quartos_ocupados' => null, 'capacidade_maxima_quartos' => null, 'data_referencia' => '2026-07-19', 'criado_em' => '2026-07-19 21:00:00']
        ];

        foreach ($fluxos as $f) {
            $db->table('fluxos_ocupacao')->insert($f);
        }

        // 5. DADOS DA TABELA: pesquisa (Preservado)
        $pesquisas = [
            [
                'id_pesquisa' => 1,
                'id_estabelecimento' => 1,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 650.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-13 11:20:00'
            ],
            [
                'id_pesquisa' => 2,
                'id_estabelecimento' => 1,
                'cidade_origem' => 'São Paulo - SP',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1200.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-14 14:15:00'
            ],
            [
                'id_pesquisa' => 3,
                'id_estabelecimento' => 1,
                'cidade_origem' => 'Contagem - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 480.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-20 18:30:00'
            ],
            [
                'id_pesquisa' => 4,
                'id_estabelecimento' => 1,
                'cidade_origem' => 'Rio de Janeiro - RJ',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1500.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-21 10:00:00'
            ],

            [
                'id_pesquisa' => 5,
                'id_estabelecimento' => 2,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 180.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-20 21:00:00'
            ],
            [
                'id_pesquisa' => 6,
                'id_estabelecimento' => 2,
                'cidade_origem' => 'Nova Lima - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 120.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'outro',
                'respondido_em' => '2026-06-20 22:30:00'
            ],
            [
                'id_pesquisa' => 7,
                'id_estabelecimento' => 2,
                'cidade_origem' => 'Sete Lagoas - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 250.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-27 19:15:00'
            ],
            [
                'id_pesquisa' => 8,
                'id_estabelecimento' => 2,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 310.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-27 21:40:00'
            ],

            [
                'id_pesquisa' => 9,
                'id_estabelecimento' => 3,
                'cidade_origem' => 'São Paulo - SP',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 2200.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'negocios',
                'respondido_em' => '2026-06-15 08:30:00'
            ],
            [
                'id_pesquisa' => 10,
                'id_estabelecimento' => 3,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 800.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-22 10:00:00'
            ],
            [
                'id_pesquisa' => 11,
                'id_estabelecimento' => 3,
                'cidade_origem' => 'Campinas - SP',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1850.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'negocios',
                'respondido_em' => '2026-06-29 11:15:00'
            ],
            [
                'id_pesquisa' => 12,
                'id_estabelecimento' => 3,
                'cidade_origem' => 'Rio de Janeiro - RJ',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 3000.00,
                'satisfacao_estrelas' => 3,
                'nps' => 6,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-04 15:30:00'
            ],

            [
                'id_pesquisa' => 13,
                'id_estabelecimento' => 4,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 390.00,
                'satisfacao_estrelas' => 4,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-14 16:00:00'
            ],
            [
                'id_pesquisa' => 14,
                'id_estabelecimento' => 4,
                'cidade_origem' => 'Betim - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 450.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-28 11:45:00'
            ],

            [
                'id_pesquisa' => 15,
                'id_estabelecimento' => 5,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 150.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-27 18:00:00'
            ],
            [
                'id_pesquisa' => 16,
                'id_estabelecimento' => 5,
                'cidade_origem' => 'Contagem - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 220.00,
                'satisfacao_estrelas' => 4,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-27 20:30:00'
            ],

            [
                'id_pesquisa' => 17,
                'id_estabelecimento' => 6,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 45.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-14 10:30:00'
            ],
            [
                'id_pesquisa' => 18,
                'id_estabelecimento' => 6,
                'cidade_origem' => 'Ouro Preto - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 85.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-21 15:45:00'
            ],
            [
                'id_pesquisa' => 19,
                'id_estabelecimento' => 6,
                'cidade_origem' => 'São Paulo - SP',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'airbnb_aluguel',
                'valor_gasto_estimado' => 350.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-28 14:00:00'
            ],

            [
                'id_pesquisa' => 20,
                'id_estabelecimento' => 7,
                'cidade_origem' => 'Nova Lima - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 20.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-21 09:30:00'
            ],
            [
                'id_pesquisa' => 21,
                'id_estabelecimento' => 7,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 60.00,
                'satisfacao_estrelas' => 4,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-06-28 11:00:00'
            ],

            [
                'id_pesquisa' => 22,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 180.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-10 20:30:00'
            ],
            [
                'id_pesquisa' => 23,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 650.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-10 22:45:00'
            ],
            [
                'id_pesquisa' => 24,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Contagem - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 210.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-11 15:30:00'
            ],
            [
                'id_pesquisa' => 25,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Divinópolis - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'airbnb_aluguel',
                'valor_gasto_estimado' => 550.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-11 19:15:00'
            ],
            [
                'id_pesquisa' => 26,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Juiz de Fora - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 890.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-11 22:10:00'
            ],
            [
                'id_pesquisa' => 27,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 140.00,
                'satisfacao_estrelas' => 3,
                'nps' => 5,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-12 14:00:00'
            ],
            [
                'id_pesquisa' => 28,
                'id_estabelecimento' => 8,
                'cidade_origem' => 'Nova Lima - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 95.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-12 17:30:00'
            ],

            [
                'id_pesquisa' => 29,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 220.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-15 19:40:00'
            ],
            [
                'id_pesquisa' => 30,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'São Paulo - SP',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1450.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-16 13:10:00'
            ],
            [
                'id_pesquisa' => 31,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Nova Lima - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 130.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-16 21:00:00'
            ],
            [
                'id_pesquisa' => 32,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Contagem - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 190.00,
                'satisfacao_estrelas' => 4,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-17 16:30:00'
            ],
            [
                'id_pesquisa' => 33,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Betim - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 240.00,
                'satisfacao_stars' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-17 22:15:00'
            ],
            [
                'id_pesquisa' => 34,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Rio de Janeiro - RJ',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1100.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-18 11:00:00'
            ],
            [
                'id_pesquisa' => 35,
                'id_estabelecimento' => 9,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 175.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-10-18 17:00:00'
            ],

            [
                'id_pesquisa' => 36,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 280.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-15 19:30:00'
            ],
            [
                'id_pesquisa' => 37,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Conselheiro Lafaiete - MG',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 850.00,
                'satisfacao_estrelas' => 5,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-16 14:10:00'
            ],
            [
                'id_pesquisa' => 38,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Nova Lima - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 150.00,
                'satisfacao_estrelas' => 4,
                'nps' => 8,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-16 21:15:00'
            ],
            [
                'id_pesquisa' => 39,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Contagem - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 240.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-17 22:40:00'
            ],
            [
                'id_pesquisa' => 40,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Betim - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 320.00,
                'satisfacao_estrelas' => 4,
                'nps' => 9,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-18 16:30:00'
            ],
            [
                'id_pesquisa' => 41,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Rio de Janeiro - RJ',
                'tempo_permanencia' => 'dormir',
                'local_hospedagem' => 'hotel_pousada',
                'valor_gasto_estimado' => 1250.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-18 20:00:00'
            ],
            [
                'id_pesquisa' => 42,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Belo Horizonte - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 195.00,
                'satisfacao_estrelas' => 5,
                'nps' => 10,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-19 15:40:00'
            ],
            [
                'id_pesquisa' => 43,
                'id_estabelecimento' => 10,
                'cidade_origem' => 'Sabará - MG',
                'tempo_permanencia' => 'bate_volta',
                'local_hospedagem' => null,
                'valor_gasto_estimado' => 160.00,
                'satisfacao_estrelas' => 3,
                'nps' => 7,
                'motivo_visita' => 'lazer',
                'respondido_em' => '2026-07-19 18:00:00'
            ]
        ];

        foreach ($pesquisas as $p) {
            $db->table('pesquisa')->insert($p);
        }

        return "Banco de dados limpo e semeado por completo com dados REAIS de Nova Lima para a demonstração!";
    }

    // ====================================================================
// ADICIONE ESTE MÉTODO AO FINAL DO SEU AuthController.php
// (Pode colar logo abaixo do método semearBanco() que você já possui)
// ====================================================================

    public function executarMigracaoVantagens()
    {
        // Restringe o acesso apenas para segurança (opcional, mas bom para TCC)
        // Se quiser facilitar o teste, pode deixar aberto para rodar no navegador
        $db = \Config\Database::connect();

        $db->transStart();

        try {
            // 1. Atualiza a tabela de estabelecimentos
            $db->query("ALTER TABLE `estabelecimento_evento` 
                    ADD COLUMN IF NOT EXISTS `aceita_desconto` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tipo`,
                    ADD COLUMN IF NOT EXISTS `pin_validacao` VARCHAR(4) NULL DEFAULT NULL AFTER `aceita_desconto`;");

            // 2. Atualiza a tabela de pesquisas
            $db->query("ALTER TABLE `pesquisa` 
                    ADD COLUMN IF NOT EXISTS `cpf` VARCHAR(11) NULL DEFAULT NULL AFTER `motivo_visita`,
                    ADD COLUMN IF NOT EXISTS `device_hash` VARCHAR(64) NULL DEFAULT NULL AFTER `cpf`,
                    ADD COLUMN IF NOT EXISTS `is_morador` TINYINT(1) NOT NULL DEFAULT 0 AFTER `device_hash`;");

            // 3. Cria a tabela de moradores locais de Nova Lima
            $db->query("CREATE TABLE IF NOT EXISTS `morador_novalima` (
                      `id_morador` INT(11) NOT NULL AUTO_INCREMENT,
                      `cpf` VARCHAR(11) NOT NULL,
                      `nome` VARCHAR(150) NOT NULL,
                      `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                      PRIMARY KEY (`id_morador`),
                      UNIQUE KEY `cpf_unico` (`cpf`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

            // 4. Insere os moradores de simulação de forma segura para não duplicar se rodar mais de uma vez
            $db->query("INSERT IGNORE INTO `morador_novalima` (`cpf`, `nome`) VALUES
                    ('11111111111', 'Ana Souza (Moradora Teste 1)'),
                    ('22222222222', 'Bruno Lima (Morador Teste 2)'),
                    ('33333333333', 'Carlos Eduardo (Morador Teste 3)');");

            $db->transComplete();

            if ($db->transStatus() === false) {
                return "Erro ao executar transação de migração no banco da Railway.";
            }

            return "<h3>Sucesso! 🎉</h3><p>O banco de dados na Railway foi atualizado e as tabelas e colunas do ecossistema antifraude/recompensas já estão ativas!</p><p>Você já pode remover este método e a rota temporária se desejar.</p>";

        } catch (\Exception $e) {
            $db->transRollback();
            return "<h3>Erro na migração:</h3><p style='color:red;'>" . $e->getMessage() . "</p>";
        }
    }
}