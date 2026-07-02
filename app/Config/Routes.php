<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rotas de Teste / Boas-vindas
$routes->get('/', 'Home::index');
$routes->get('teste', 'Home::teste');

/*
 ====================================================================
  1. ROTAS PÚBLICAS (Acessíveis por qualquer um / Sem Filtro)
 ====================================================================
*/
$routes->group('api', function ($routes) {
    // Autenticação
    $routes->post('login', 'AuthController::login');
    $routes->post('logout', 'AuthController::logout');

    // Cadastro Inicial do Lojista (Gera o status 'pendente')
    $routes->post('solicitar-cadastro', 'AuthController::registrarPendente');

    // Coleta do Turista (Waron envia os dados do QR Code para cá)
    $routes->post('pesquisa', 'PesquisaController::salvar');
});

/*
 ====================================================================
  2. GRUPO ADMINISTRATIVO (Prefeitura - Protegido por Filtro)
 ====================================================================
*/
$routes->group('api/admin', ['filter' => 'authAdmin'], function ($routes) {
    $routes->get('dashboard', 'AdminController::index');          // Dashboard com gráficos e filtros
    $routes->get('aprovacoes', 'AdminController::listarPendentes'); // Lista lojistas pendentes
    $routes->put('aprovar/(:num)', 'AdminController::aprovarLojista/$1'); // Aprova o lojista pelo ID
    $routes->get('relatorios', 'AdminController::exportarCSV');    // Exportação para ICMS Turismo

    // [ADICIONADO] Criação de locais fixos ou eventos sazonais protegida por login
    $routes->post('salvar-local', 'AdminController::salvarLocalOuEvento');
});

/*
 ====================================================================
  3. GRUPO DO EMPREENDEDOR (Lojista Ativo - Protegido por Filtro)
 ====================================================================
*/
$routes->group('api/painel', ['filter' => 'authLojista'], function ($routes) {
    $routes->get('meu-negocio', 'LojistaController::index');      // Dados do estabelecimento dele
    $routes->post('ocupacao', 'LojistaController::lancarInsumo');  // [UNIFICADO] Lançamento semanal
});
