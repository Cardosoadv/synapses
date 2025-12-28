<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to, if
     * non is specified.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * The available auth groups in the system.
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Controle total do sistema.',
        ],
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Administradores do dia a dia.',
        ],
        'advogado' => [
            'title'       => 'Advogado',
            'description' => 'Acesso ao módulo Jurídico.',
        ],
        'financeiro' => [
            'title'       => 'Financeiro',
            'description' => 'Acesso ao módulo Financeiro.',
        ],
        'fiscal' => [
            'title'       => 'Fiscal',
            'description' => 'Acesso ao módulo de Fiscalização.',
        ],
        'user' => [
            'title'       => 'Usuário Padrão',
            'description' => 'Acesso básico ao sistema.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Pode acessar a área administrativa',
        'users.manage-admins' => 'Pode gerenciar administradores',
        'users.create'        => 'Pode criar usuários',
        'users.edit'          => 'Pode editar usuários',
        'users.delete'        => 'Pode excluir usuários',
        'juridico.access'     => 'Pode acessar módulo jurídico',
        'financeiro.access'   => 'Pode acessar módulo financeiro',
        'fiscal.access'       => 'Pode acessar módulo fiscalização',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'juridico.*',
            'financeiro.*',
            'fiscal.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
        ],
        'advogado' => [
            'juridico.access',
        ],
        'financeiro' => [
            'financeiro.access',
        ],
        'fiscal' => [
            'fiscal.access',
        ],
        'user' => [],
    ];
}
