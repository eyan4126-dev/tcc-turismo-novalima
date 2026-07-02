<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
 ====================================================================
  1. ROTAS DE EXIBIÇÃO DE TELAS (VIEW - GET)
 ====================================================================
*/

// Rota raiz "/" vai direto para a tela de login
$routes->get('/', 'AuthController::index');
$routes->get('login', 'AuthController::index');

// Rota para abrir o formulário da pesquisa do turista
// Exemplo de acesso: localhost/turismo-hub/public/pesquisa
$routes->get('pesquisa', 'PesquisaController::index');


/*
 ====================================================================
  2. ROTAS PÚBLICAS DA API (PROCESSAMENTO - POST)
 ====================================================================
*/
$routes->group('api', function ($routes) {

    // Processa os dados digitados no formulário de login (view login)
    $routes->post('login', 'AuthController::login');
    $routes->post('logout', 'AuthController::logout');

    // Recebe o formulário de pré-cadastro (solicitado na tela de login)
    $routes->post('registrar-lojista', 'AuthController::registrarPendente');

    // Recebe as respostas que o Waron estruturou no formulário do turista (view pesquisa)
    $routes->post('pesquisa', 'PesquisaController::salvar');
});


/*
 ====================================================================
  3. ROTAS FUTURAS (Comentadas para não dar erro de Controller Ausente)
 ====================================================================
 * Quando vocês criarem as telas do Admin e do Lojista, basta remover os
 * comentários '/*' das seções abaixo para ativar os filtros de segurança.
 */

/*
$routes->group('api/admin', ['filter' => 'authAdmin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('aprovacoes', 'AdminController::listarPendentes');
    $routes->put('aprovar/(:num)', 'AdminController::aprovarLojista/$1');
});

$routes->group('api/painel', ['filter' => 'authLojista'], function ($routes) {
    $routes->get('meu-negocio', 'LojistaController::index');
    $routes->post('ocupacao', 'LojistaController::lancarInsumo');
});
*/