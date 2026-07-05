<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- 1. ROTAS PARA EXIBIR AS TELAS (GET) ---
$routes->get('/', function () {
    return view('login');
});
$routes->get('login', function () {
    return view('login');
});
$routes->get('cadastro', function () {
    return view('cadastro');
});
$routes->get('pesquisa', function () {
    return view('pesquisa');
});

// Adicione estas linhas abaixo das suas declarações existentes do AdminController:
$routes->get('admin', 'AdminController::index');
$routes->get('estabelecimentos', 'AdminController::estabelecimentos');
$routes->get('qrcodes', 'AdminController::qrcodes');

// Endpoints de processamento de Onboarding e Cadastro Direto
$routes->post('admin/aprovar/(:num)', 'AdminController::aprovarLojista/$1');
$routes->post('admin/recusar/(:num)', 'AdminController::recusarLojista/$1');
$routes->post('estabelecimentos/salvar-direto', 'AdminController::salvarDireto');

$routes->get('painel', 'AuthController::painel');
$routes->get('logout', 'AuthController::logout'); // Rota para gerenciar o encerramento de sessão

// --- 2. ROTAS DE PROCESSAMENTO (POST/API) ---
$routes->post('api/login', 'AuthController::login');
$routes->post('api/logout', 'AuthController::logout');
$routes->post('api/registrar-lojista', 'AuthController::cadastrarLojista');
$routes->post('api/pesquisa', 'PesquisaController::salvar');

// Alimenta o JavaScript do painel com os dados do usuário ativo
$routes->get('api/auth/sessao-atual', 'AuthController::obterSessao');

// Grupo Restrito: Prefeitura (Admin API)
$routes->group('api/admin', ['filter' => 'authAdmin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('aprovacoes', 'AdminController::listarPendentes');
    $routes->put('aprovar/(:num)', 'AdminController::aprovarLojista/$1');
    $routes->get('relatorios', 'AdminController::exportarCSV');
});

// Grupo Restrito: Empreendedor (Lojista)
$routes->group('api/painel', ['filter' => 'authLojista'], function ($routes) {
    $routes->get('meu-negocio', 'LojistaController::index');
    $routes->post('ocupacao', 'LojistaController::lancarOcupacao');
});

$routes->group('lojista', function ($routes) {
    $routes->get('', 'LojistaController::index');
    $routes->get('dashboard', 'LojistaController::index');
    $routes->get('qrcode', 'LojistaController::qrcode');
});
