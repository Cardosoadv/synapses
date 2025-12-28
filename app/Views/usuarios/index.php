<?= $this->extend('template/layout') ?>


<?= $this->section('header') ?>
<style>
    /* Estilos adicionais para os cards de usuário */
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .stretched-link::after {
        z-index: 1;
    }

    .min-w-0 {
        min-width: 0;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Fix para botões dentro de cards com stretched-link */
    .card-body .btn {
        position: relative;
        z-index: 10;
    }

    /* Melhor visualização dos badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .flex-grow-1 .text-truncate {
            max-width: 180px;
        }
    }

    /* Animação suave para hover nos botões */
    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: scale(1.02);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('barraPesquisa') ?>
<!-- Search Form -->
<form action="" method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="s" class="form-control" placeholder="Pesquisar por nome, email..."
            aria-label="Pesquisar" value="<?= esc($search ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i> Pesquisar
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-danger">
                <i class="bi bi-x-circle"></i> Limpar
            </a>
        <?php endif; ?>
    </div>
</form>

<div class="row mb-3">
    <div class="col-12 text-end">
        <a href="<?= base_url('usuarios/novo/') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="mt-3">
    <?php if (empty($users)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Nenhum usuário encontrado.
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="alert alert-info mb-0 py-2 px-3">
                <i class="bi bi-people"></i>
                <strong><?= $pager->getTotal() ?></strong> usuário(s) encontrado(s).
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($users as $user): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body p-3">
                            <!-- Header com Foto e Informações Principais -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 position-relative">
                                    <?php if (!empty($user['auth_image'])): ?>
                                        <img src="<?= base_url('usuarios/exibirFoto/' . $user['id']) ?>"
                                            alt="Foto de <?= esc($user['username']) ?>"
                                            class="rounded-circle border border-2"
                                            style="width: 56px; height: 56px; object-fit: cover; border-color: #dee2e6 !important;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-gradient d-flex justify-content-center align-items-center text-white fw-bold shadow-sm"
                                            style="width: 56px; height: 56px; font-size: 22px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Status Badge na Foto -->
                                    <span class="position-absolute bottom-0 end-0 translate-middle-y badge rounded-pill <?= isset($user['active']) && $user['active'] ? 'bg-success' : 'bg-secondary' ?>"
                                        style="font-size: 0.65rem; padding: 0.25rem 0.45rem;"
                                        title="<?= isset($user['active']) && $user['active'] ? 'Ativo' : 'Inativo' ?>">
                                        <i class="bi <?= isset($user['active']) && $user['active'] ? 'bi-check-lg' : 'bi-x-lg' ?>"></i>
                                    </span>
                                </div>

                                <div class="flex-grow-1 ms-3 min-w-0">
                                    <h6 class="card-title mb-1 fw-bold text-truncate" title="<?= esc($user['username']) ?>">
                                        <a href="<?= base_url('usuarios/editar/' . $user['id']) ?>"
                                            class="text-decoration-none text-dark stretched-link">
                                            <?= esc($user['username']) ?>
                                        </a>
                                    </h6>
                                    <div class="text-muted small text-truncate" title="<?= esc($user['email']) ?>">
                                        <i class="bi bi-envelope me-1"></i><?= esc($user['email']) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Grupos/Roles -->
                            <?php if (!empty($user['groups'])): ?>
                                <div class="mb-2 d-flex flex-wrap gap-1">
                                    <?php foreach ($user['groups'] as $group): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                            style="font-weight: 500; padding: 0.35rem 0.65rem;">
                                            <?= esc(ucfirst($group)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Informações Adicionais -->
                            <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                                <div title="Permissões">
                                    <i class="bi bi-shield-lock text-primary"></i>
                                    <span class="ms-1"><?= $user['permissions_count'] ?? 0 ?> perm.</span>
                                </div>

                                <?php if (isset($user['last_active'])): ?>
                                    <div class="text-truncate ms-2"
                                        style="max-width: 160px;"
                                        title="Último acesso: <?= date('d/m/Y H:i', strtotime($user['last_active'])) ?>">
                                        <i class="bi bi-clock-history text-success"></i>
                                        <span class="ms-1"><?= date('d/m/Y H:i', strtotime($user['last_active'])) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Ações -->
                            <div class="d-flex gap-2 mt-3 pt-2 border-top position-relative">
                                <a href="<?= base_url('usuarios/editar/' . $user['id']) ?>"
                                    class="btn btn-sm btn-outline-primary flex-fill position-relative"
                                    style="z-index: 10;">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="<?= base_url('usuarios/excluir/' . $user['id']) ?>"
                                    class="btn btn-sm btn-outline-danger position-relative"
                                    style="z-index: 10;"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('conteudoSidebar') ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informações</h6>
    </div>
    <div class="card-body">
        <p class="small text-muted mb-3">
            Gerencie os usuários do sistema, atribua grupos e permissões específicas para controlar o acesso.
        </p>
        <div class="d-grid gap-2">
            <a href="<?= base_url('usuarios/novo') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i> Adicionar Usuário
            </a>
            <a href="<?= base_url('usuarios/grupos') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i> Gerenciar Grupos
            </a>
        </div>
    </div>
</div>

<!-- Estatísticas Rápidas -->
<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Estatísticas</h6>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <span class="small">Total de Usuários</span>
            <strong class="text-primary"><?= $pager->getTotal() ?></strong>
        </div>
        <?php if (isset($activeUsers)): ?>
            <div class="d-flex justify-content-between">
                <span class="small">Usuários Ativos</span>
                <strong class="text-success"><?= $activeUsers ?></strong>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>