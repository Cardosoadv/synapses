<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/**
 * Rotas de autenticação
 */
service('auth')->routes($routes);

/**
 * Rotas de usuários
 */
$routes->group('usuarios', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Usuarios::index');
    $routes->get('novo', 'Usuarios::novo', ['filter' => 'permission:users.create']);
    $routes->post('salvar', 'Usuarios::salvar', ['filter' => 'permission:users.create']);
    $routes->get('editar/(:num)', 'Usuarios::editar/$1', ['filter' => 'permission:users.edit']);
    $routes->post('atualizar/(:num)', 'Usuarios::atualizar/$1', ['filter' => 'permission:users.edit']);
    $routes->get('excluir/(:num)', 'Usuarios::excluir/$1', ['filter' => 'permission:users.delete']);
    $routes->get('promover/(:num)', 'Usuarios::promover/$1', ['filter' => 'permission:users.edit']);
    $routes->get('grupos', 'Usuarios::grupos');
});

$routes->get('usuarios/exibirFoto/(:num)', 'Usuarios::exibirFoto/$1');

/**
 * Rotas de funcionários
 */
$routes->group('funcionarios', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Funcionarios::index');
    $routes->get('novo', 'Funcionarios::novo', ['filter' => 'permission:users.create']);
    $routes->post('salvar', 'Funcionarios::salvar', ['filter' => 'permission:users.create']);
    $routes->get('editar/(:num)', 'Funcionarios::editar/$1', ['filter' => 'permission:users.edit']);
    $routes->post('atualizar/(:num)', 'Funcionarios::atualizar/$1', ['filter' => 'permission:users.edit']);
    $routes->get('excluir/(:num)', 'Funcionarios::excluir/$1', ['filter' => 'permission:users.delete']);
    $routes->get('exibirFoto/(:num)', 'Funcionarios::exibirFoto/$1');
});

/**
 * Rotas de profissionais
 */
$routes->group('profissionais', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Profissionais::index');
    $routes->get('new', 'Profissionais::novo', ['filter' => 'permission:users.create']); // Using 'new' to match standard resource but mapped to 'novo'
    $routes->post('create', 'Profissionais::salvar', ['filter' => 'permission:users.create']);
    $routes->get('edit/(:num)', 'Profissionais::editar/$1', ['filter' => 'permission:users.edit']);
    $routes->post('update/(:num)', 'Profissionais::atualizar/$1', ['filter' => 'permission:users.edit']);
    $routes->get('delete/(:num)', 'Profissionais::excluir/$1', ['filter' => 'permission:users.delete']);
    $routes->get('history/(:num)', 'Profissionais::history/$1');
});

/**
 * Rotas Auxiliares
 */
$routes->group('profissoes', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Profissoes::index');
    $routes->get('novo', 'Profissoes::novo');
    $routes->post('salvar', 'Profissoes::salvar');
    $routes->get('editar/(:num)', 'Profissoes::editar/$1');
    $routes->post('atualizar/(:num)', 'Profissoes::atualizar/$1');
    $routes->get('excluir/(:num)', 'Profissoes::excluir/$1');
});

$routes->group('categorias_profissionais', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'CategoriasProfissionais::index');
    $routes->get('novo', 'CategoriasProfissionais::novo');
    $routes->post('salvar', 'CategoriasProfissionais::salvar');
    $routes->get('editar/(:num)', 'CategoriasProfissionais::editar/$1');
    $routes->post('atualizar/(:num)', 'CategoriasProfissionais::atualizar/$1');
    $routes->get('excluir/(:num)', 'CategoriasProfissionais::excluir/$1');
});

$routes->group('atribuicoes', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Atribuicoes::index');
    $routes->get('novo', 'Atribuicoes::novo');
    $routes->post('salvar', 'Atribuicoes::salvar');
    $routes->get('editar/(:num)', 'Atribuicoes::editar/$1');
    $routes->post('atualizar/(:num)', 'Atribuicoes::atualizar/$1');
    $routes->get('excluir/(:num)', 'Atribuicoes::excluir/$1');
});
