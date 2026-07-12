<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- 1. ROTAS PÚBLICAS (Acessíveis sem login) ---
$routes->get('/', function () {
    return view('login');
});
$routes->get('login', function () {
    return view('login');
});
$routes->get('cadastro', function () {
    return view('cadastro');
});

// A rota passa pelo Controller para carregar o nome do local dinamicamente
$routes->get('pesquisa', 'PesquisaController::index');

// --- 2. ROTAS DE PROCESSAMENTO DE AUTENTICAÇÃO ---
$routes->post('api/login', 'AuthController::login');
$routes->post('api/logout', 'AuthController::logout');
$routes->post('api/registrar-lojista', 'AuthController::cadastrarLojista');
$routes->get('logout', 'AuthController::logout');

// Alimenta o JavaScript do painel com os dados do usuário ativo
$routes->get('api/auth/sessao-atual', 'AuthController::obterSessao');
$routes->get('painel', 'AuthController::painel');

// --- 3. FLUXO DO TURISTA (Submissão da Pesquisa e Validação do PIN) ---
$routes->post('api/pesquisa', 'PesquisaController::salvar');
$routes->post('api/pesquisa/validar-pin', 'PesquisaController::validarPin');

// --- 4. FLUXO DO TURISTA (Escaneamento do QR Code) ---
$routes->get('turismo/visitar/(:any)', 'PesquisaController::index');


// ====================================================================
// ECOSSISTEMA ADMIN (Protegido por AdminAuthFilter)
// ====================================================================
$routes->group('admin', ['filter' => 'AdminAuthFilter'], function ($routes) {
    // Telas Principais do Admin
    $routes->get('', 'AdminController::index');
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('estabelecimentos', 'AdminController::estabelecimentos');
    $routes->get('qrcodes', 'AdminController::qrcodes');

    // 🏛️ MÓDULO 3: Tela de Consolidação Web
    $routes->get('consolidador', 'AdminController::consolidador');

    // 📊 MÓDULO 2: Downloads Diretos das Exportações
    $routes->post('exportar-icms', 'AdminController::exportarIcms');
    $routes->post('exportar-sismapa', 'AdminController::exportarSismapa');

    // Processamento de Cadastros e Ações Diretas da Tela
    $routes->post('aprovarLojista/(:num)', 'AdminController::aprovarLojista/$1');
    $routes->post('recusarLojista/(:num)', 'AdminController::recusarLojista/$1');
});

// 🏛️ ROTA DE SALVAMENTO DIRETO
$routes->post('estabelecimentos/salvar-direto', 'AdminController::salvarDireto', ['filter' => 'AdminAuthFilter']);

// Mantido por compatibilidade
$routes->get('estabelecimentos', 'AdminController::estabelecimentos', ['filter' => 'AdminAuthFilter']);
$routes->get('qrcodes', 'AdminController::qrcodes', ['filter' => 'AdminAuthFilter']);

// Rotas de API internas do Admin (Protegidas por AdminAuthFilter)
$routes->group('api/admin', ['filter' => 'AdminAuthFilter'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('aprovacoes', 'AdminController::listarPendentes');
    $routes->put('aprovar/(:num)', 'AdminController::aprovarLojista/$1');

    // 🏛️ MÓDULO 2: Endpoints de API para Exportadores Fiscais Alinhados
    $routes->get('exportar/icms-turismo', 'AdminController::exportarIcmsTurismo');
    $routes->get('exportar/sismapa', 'AdminController::exportarSismapa');

    // 📈 MÓDULO 3: Motor de API para Consolidação e Cruzamento de Dados
    $routes->get('dados/indicadores-gerais', 'AdminController::apiIndicadoresGerais');
    $routes->get('dados/cruzamento-motivo', 'AdminController::apiCruzamentoMotivo');
    $routes->get('dados/cruzamento-permanencia', 'AdminController::apiCruzamentoPermanencia');
});


// ====================================================================
// ECOSSISTEMA LOJISTA (Protegido por LojistaAuthFilter)
// ====================================================================
$routes->group('lojista', ['filter' => 'LojistaAuthFilter'], function ($routes) {
    // Telas Principais do Lojista
    $routes->get('', 'LojistaController::index');
    $routes->get('dashboard', 'LojistaController::index');
    $routes->get('qrcode', 'LojistaController::qrcode');

    // Atualiza desconto e foto de perfil sob o Programa DesconTour
    $routes->post('atualizar-desconto', 'LojistaController::atualizarDesconto');
});

// Rotas de API internas do Lojista
$routes->group('api/painel', ['filter' => 'LojistaAuthFilter'], function ($routes) {
    $routes->get('meu-negocio', 'LojistaController::index');
    $routes->post('ocupacao', 'LojistaController::lancarOcupacao');
});

// --- ROTAS DO NOVO GUIA TURÍSTICO ---
$routes->get('guia', 'PesquisaController::guia');
$routes->get('pesquisa/sucesso', 'PesquisaController::sucesso');

// --- SEMEADORES E MIGRAÇÕES AUTOMÁTICAS ---
$routes->get('banco', 'AuthController::semearBanco');
$routes->get('migrar-banco', 'AuthController::executarMigracaoVantagens');
$routes->get('semear-parceiros', 'AuthController::semearNovosParceirosReais');
$routes->get('migrar-banco-foto', 'AuthController::executarMigracaoFoto');

// NOVA ROTA: Roda a atualização do Programa DesconTour adicionando a porcentagem flexível
$routes->get('migrar-banco-descontour', 'AuthController::executarMigracaoDesconTour');