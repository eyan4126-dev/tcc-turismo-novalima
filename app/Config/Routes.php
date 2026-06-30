<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('teste', 'Home::teste');

// Rotas Públicas (Login e Envio de Pesquisa)
$routes->post('api/login', 'AuthController::login');
$routes->post('api/logout', 'AuthController::logout');
$routes->post('api/pesquisa', 'PesquisaController::salvar'); // Front do turista envia sem login

// Grupo Restrito: Prefeitura (Admin)
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

// Rota pública consumida pelo formulário do turista (Waron)
$routes->post('api/pesquisa', 'PesquisaController::salvar');
