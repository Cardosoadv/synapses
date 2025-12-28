<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Sidebar Navigation (in sync with index but can be independent) -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informações</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Visualização dos grupos de acesso e permissões configuradas no sistema.
                    Estas configurações são definidas globalmente.
                </p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Voltar para Usuários
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <ul class="nav nav-tabs mb-3" id="groupsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab" aria-controls="groups" aria-selected="true">
                    Grupos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab" aria-controls="permissions" aria-selected="false">
                    Todas as Permissões
                </button>
            </li>
        </ul>

        <div class="tab-content" id="groupsTabContent">
            <!-- Groups Tab -->
            <div class="tab-pane fade show active" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                <div class="d-grid gap-3">
                    <?php foreach ($groups as $groupKey => $groupInfo): ?>
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= esc($groupInfo['title']) ?> (<code><?= esc($groupKey) ?></code>)</h6>
                                    <small class="text-muted"><?= esc($groupInfo['description']) ?></small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <?= isset($matrix[$groupKey]) ? count($matrix[$groupKey]) : 0 ?> Permissões
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="small fw-bold text-uppercase text-muted mb-2">Permissões Atribuídas</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if (isset($matrix[$groupKey]) && !empty($matrix[$groupKey])): ?>
                                        <?php foreach ($matrix[$groupKey] as $perm): ?>
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($perm) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Nenhuma permissão específica (pode herdar tudo se for Super Admin).</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Permissions Tab -->
            <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Chave da Permissão</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($permissions as $permKey => $permDesc): ?>
                                        <tr>
                                            <td>
                                                <code class="text-primary fw-bold"><?= esc($permKey) ?></code>
                                            </td>
                                            <td><?= esc($permDesc) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>