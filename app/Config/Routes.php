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
