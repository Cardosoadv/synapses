<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-gear"></i>
            <?= isset($funcionario) ? 'Editar Funcionário' : 'Novo Funcionário' ?>
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($funcionario) ? base_url('funcionarios/atualizar/' . $funcionario['id']) : base_url('funcionarios/salvar') ?>"
            method="post" enctype="multipart/form-data">

            <!-- Basic Information -->
            <h6 class="border-bottom pb-2 mb-3">Dados Cadastrais</h6>
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <div class="mb-2">
                        <?php if (isset($funcionario) && !empty($funcionario['photo'])): ?>
                            <img src="<?= base_url('funcionarios/exibirFoto/' . $funcionario['id']) ?>"
                                class="rounded-circle border"
                                style="width: 150px; height: 150px; object-fit: cover;"
                                id="previewFoto">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white mx-auto"
                                style="width: 150px; height: 150px; font-size: 48px;"
                                id="previewPlaceholder">
                                <i class="bi bi-person"></i>
                            </div>
                            <img src="" class="rounded-circle border d-none"
                                style="width: 150px; height: 150px; object-fit: cover;"
                                id="previewFotoReal">
                        <?php endif; ?>
                    </div>
                    <label class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-camera"></i> Alterar Foto
                        <input type="file" name="foto-perfil" class="d-none" onchange="previewImage(this)">
                    </label>
                </div>

                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome de Usuário (Login) <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required
                                value="<?= old('username', $funcionario['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Corporativo <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= old('email', $funcionario['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Matrícula <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" class="form-control" required
                                value="<?= old('registration_number', $funcionario['registration_number'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cargo <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control" required
                                value="<?= old('position', $funcionario['position'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <input type="text" name="department" class="form-control"
                                value="<?= old('department', $funcionario['department'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= isset($funcionario) ? 'Nova Senha (deixe em branco para manter)' : 'Senha' ?> <span class="text-danger"><?= isset($funcionario) ? '' : '*' ?></span></label>
                            <input type="password" name="password" class="form-control" <?= isset($funcionario) ? '' : 'required' ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Senha <span class="text-danger"><?= isset($funcionario) ? '' : '*' ?></span></label>
                            <input type="password" name="pass_confirm" class="form-control" <?= isset($funcionario) ? '' : 'required' ?>>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="active" value="1" id="activeCheck"
                                    <?= (!isset($funcionario) || (isset($funcionario['status']) && $funcionario['status'] === 'active')) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="activeCheck">Funcionário Ativo (Acesso ao Sistema)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Groups & Permissions -->
            <?php
            $config = config('AuthGroups');
            $userGroups = $funcionario['groups'] ?? [];
            $userPermissions = $funcionario['permissions'] ?? [];
            ?>

            <h6 class="border-bottom pb-2 mb-3 mt-4">Perfil de Acesso e Permissões</h6>
            <div class="mb-3">
                <label class="form-label d-block">Grupos de Acesso</label>
                <div class="row">
                    <?php foreach ($config->groups as $groupKey => $groupInfo): ?>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="groups[]" value="<?= $groupKey ?>"
                                    id="group_<?= $groupKey ?>"
                                    <?= in_array($groupKey, $userGroups) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="group_<?= $groupKey ?>">
                                    <?= esc($groupInfo['title']) ?>
                                    <i class="bi bi-info-circle text-muted" title="<?= esc($groupInfo['description']) ?>"></i>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3 mt-4">Permissões Específicas</h6>
            <div class="alert alert-light border small">
                <i class="bi bi-exclamation-circle"></i> Permissões concedidas através de Grupos não precisam ser marcadas aqui. Use esta seção para conceder permissões extras granulares.
            </div>

            <div class="row">
                <?php foreach ($config->permissions as $permKey => $permDesc):
                    $parts = explode('.', $permKey);
                    $prefix = strtoupper($parts[0]);
                ?>
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permKey ?>"
                                id="perm_<?= str_replace('.', '_', $permKey) ?>"
                                <?= in_array($permKey, $userPermissions) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="perm_<?= str_replace('.', '_', $permKey) ?>">
                                <span class="badge bg-secondary me-1"><?= $prefix ?></span>
                                <?= esc($permDesc) ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                <a href="<?= base_url('funcionarios') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Funcionário
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                const placeholder = document.getElementById('previewPlaceholder');
                const imgReal = document.getElementById('previewFotoReal');
                const imgExisting = document.getElementById('previewFoto');

                if (placeholder) placeholder.classList.add('d-none');
                if (imgExisting) imgExisting.classList.add('d-none');

                imgReal.src = e.target.result;
                imgReal.classList.remove('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>
