<?= $this->extend('template/layout') ?>

<?= $this->section('header') ?>
<style>
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
        <input type="text" name="s" class="form-control" placeholder="Pesquisar por razão social, CNPJ..." aria-label="Pesquisar" value="<?= esc($search ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i> Pesquisar
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= base_url('empresas') ?>" class="btn btn-outline-danger">
                <i class="bi bi-x-circle"></i> Limpar
            </a>
        <?php endif; ?>
    </div>
</form>

<div class="row mb-3">
    <div class="col-12 text-end d-flex gap-2 justify-content-end">
        <a href="<?= base_url('empresas/vincular') ?>" class="btn btn-primary">
            <i class="bi bi-link-45deg"></i> Vincular Profissional
        </a>
        <a href="<?= base_url('empresas/novo') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nova Empresa
        </a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($empresas)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Nenhuma empresa encontrada.
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="alert alert-info mb-0 py-2 px-3">
                <i class="bi bi-building"></i>
                <strong><?= count($empresas) ?></strong> empresa(s) encontrada(s).
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($empresas as $empresa): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 56px; height: 56px; font-size: 22px;">
                                        <?= strtoupper(substr($empresa['razao_social'], 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 min-w-0">
                                    <h6 class="card-title mb-1 fw-bold text-truncate" title="<?= esc($empresa['razao_social']) ?>">
                                        <a href="<?= base_url('empresas/editar/' . $empresa['id']) ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= esc($empresa['razao_social']) ?>
                                        </a>
                                    </h6>
                                    <div class="text-muted small">
                                        <i class="bi bi-card-text me-1"></i> CNPJ: <?= esc($empresa['cnpj']) ?>
                                    </div>
                                    <?php if (!empty($empresa['nome_fantasia'])): ?>
                                        <div class="text-muted small text-truncate">
                                            <i class="bi bi-shop me-1"></i> <?= esc($empresa['nome_fantasia']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3 pt-2 border-top position-relative">
                                <a href="<?= base_url('empresas/editar/' . $empresa['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill position-relative" style="z-index: 10;">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="<?= base_url('empresas/excluir/' . $empresa['id']) ?>" class="btn btn-sm btn-outline-danger position-relative" style="z-index: 10;" onclick="return confirm('Tem certeza que deseja excluir esta empresa?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>