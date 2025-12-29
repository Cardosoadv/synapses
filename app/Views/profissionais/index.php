<?= $this->extend('template/layout') ?>

<?= $this->section('header') ?>
<style>
    .card { transition: all 0.3s ease; }
    .card:hover { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important; }
    .stretched-link::after { z-index: 1; }
    .min-w-0 { min-width: 0; }
    .btn { transition: all 0.2s ease; }
    .btn:hover { transform: scale(1.02); }
</style>
<?= $this->endSection() ?>

<?= $this->section('barraPesquisa') ?>
<!-- Search Form -->
<form action="" method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="s" class="form-control" placeholder="Pesquisar por nome, CPF..." aria-label="Pesquisar" value="<?= esc($search ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i> Pesquisar
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= base_url('profissionais') ?>" class="btn btn-outline-danger">
                <i class="bi bi-x-circle"></i> Limpar
            </a>
        <?php endif; ?>
    </div>
</form>

<div class="row mb-3">
    <div class="col-12 text-end">
        <a href="<?= base_url('profissionais/new') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Profissional
        </a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="mt-3">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($profissionais)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Nenhum profissional encontrado.
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="alert alert-info mb-0 py-2 px-3">
                <i class="bi bi-people"></i>
                <strong><?= count($profissionais) ?></strong> profissional(is) encontrado(s).
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($profissionais as $prof): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 position-relative">
                                    <?php if (!empty($prof['photo'])): ?>
                                        <img src="<?= base_url($prof['photo']) ?>" alt="Foto" class="rounded-circle border border-2" style="width: 56px; height: 56px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 56px; height: 56px; font-size: 22px;">
                                            <?= strtoupper(substr($prof['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="position-absolute bottom-0 end-0 translate-middle-y badge rounded-pill <?= ($prof['status'] == 'active') ? 'bg-success' : 'bg-secondary' ?>" style="font-size: 0.65rem;">
                                        <?= esc(ucfirst($prof['status'])) ?>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3 min-w-0">
                                    <h6 class="card-title mb-1 fw-bold text-truncate" title="<?= esc($prof['name']) ?>">
                                        <a href="<?= base_url('profissionais/edit/' . $prof['id']) ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= esc($prof['name']) ?>
                                        </a>
                                    </h6>
                                    <div class="text-muted small">
                                        <i class="bi bi-card-heading me-1"></i> CPF: <?= esc($prof['cpf']) ?>
                                    </div>
                                    <?php if(!empty($prof['registration_number'])): ?>
                                    <div class="text-muted small">
                                        <i class="bi bi-hash me-1"></i> Reg: <?= esc($prof['registration_number']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-3 pt-2 border-top position-relative">
                                <a href="<?= base_url('profissionais/history/' . $prof['id']) ?>" class="btn btn-sm btn-outline-info position-relative" style="z-index: 10;" title="Histórico">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                <a href="<?= base_url('profissionais/edit/' . $prof['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill position-relative" style="z-index: 10;">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="<?= base_url('profissionais/delete/' . $prof['id']) ?>" class="btn btn-sm btn-outline-danger position-relative" style="z-index: 10;" onclick="return confirm('Tem certeza?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
