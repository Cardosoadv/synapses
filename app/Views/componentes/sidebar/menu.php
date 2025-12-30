<?php

/**
 * Lógica necessária ao funcionamento do menu lateral
 */


$uri = service('uri');
$active = $uri->getSegment(1);
$subActive = null;
$subActive2 = null;

if ($uri->getTotalSegments() >= 2) {
    $subActive = $uri->getSegment(2);
}

if ($uri->getTotalSegments() >= 3) {
    $subActive2 = $uri->getSegment(3);
}


?>

<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
        <a href="<?= site_url(); ?>" class="nav-link <?= ($active === null || $active === 'home') ? 'active bg-primary text-white shadow-sm' : ''; ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Home</p>
        </a>
    </li>


    <?php
    /**
     * Checa permissão para exibir o menu de usuários
     */
    if (auth()->user()->can('users.create') || auth()->user()->inGroup('superadmin', 'admin')): ?>
        <li class="nav-item">
            <a href="<?= site_url('usuarios'); ?>" class="nav-link <?= ($active === 'usuarios') ? 'active bg-primary text-white shadow-sm' : ''; ?>">
                <i class="nav-icon bi bi-person-fill-gear"></i>
                <p>Usuários</p>
            </a>
        </li>
    <?php endif; ?>

    <?php
    /**
     * Checa permissão para administradores
     */
    if (auth()->user()->can('users.manage-admins')): ?>
        <li class="nav-item">
            <a href="<?= site_url('funcionarios'); ?>" class="nav-link <?= ($active === 'funcionarios') ? 'active bg-primary text-white shadow-sm' : ''; ?>">
                <i class="nav-icon bi bi-people-fill"></i>
                <p>Funcionários</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= site_url('profissionais'); ?>" class="nav-link <?= ($active === 'profissionais') ? 'active bg-primary text-white shadow-sm' : ''; ?>">
                <i class="nav-icon bi bi-briefcase-fill"></i>
                <p>Profissionais</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= site_url('empresas'); ?>" class="nav-link <?= ($active === 'empresas') ? 'active bg-primary text-white shadow-sm' : ''; ?>">
                <i class="nav-icon bi bi-building"></i>
                <p>Empresas</p>
            </a>
        </li>

        <li class="nav-item <?= in_array($active, ['profissoes', 'categorias_profissionais', 'atribuicoes']) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?= in_array($active, ['profissoes', 'categorias_profissionais', 'atribuicoes']) ? 'active bg-primary text-white' : '' ?>">
                <i class="nav-icon bi bi-table"></i>
                <p>
                    Tabelas Auxiliares
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= site_url('profissoes'); ?>" class="nav-link <?= ($active === 'profissoes') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Profissões</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('categorias_profissionais'); ?>" class="nav-link <?= ($active === 'categorias_profissionais') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Categorias</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('atribuicoes'); ?>" class="nav-link <?= ($active === 'atribuicoes') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Atribuições</p>
                    </a>
                </li>
            </ul>
        </li>

    <?php endif; ?>

</ul>