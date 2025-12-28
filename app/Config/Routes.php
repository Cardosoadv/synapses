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
$routes->group('funcionarios', ['filter' => 'group:superadmin,admin'], function ($routes) {
    $routes->get('/', 'Funcionarios::index');
    $routes->get('novo', 'Funcionarios::novo', ['filter' => 'permission:users.create']);
    $routes->post('salvar', 'Funcionarios::salvar', ['filter' => 'permission:users.create']);
    $routes->get('editar/(:num)', 'Funcionarios::editar/$1', ['filter' => 'permission:users.edit']);
    $routes->post('atualizar/(:num)', 'Funcionarios::atualizar/$1', ['filter' => 'permission:users.edit']);
    $routes->get('excluir/(:num)', 'Funcionarios::excluir/$1', ['filter' => 'permission:users.delete']);
    $routes->get('exibirFoto/(:num)', 'Funcionarios::exibirFoto/$1');
});
