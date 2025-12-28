<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-gear"></i>
            <?= isset($user) ? 'Editar Usuário' : 'Novo Usuário' ?>
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= isset($user) ? base_url('usuarios/atualizar/' . $user['id']) : base_url('usuarios/salvar') ?>"
            method="post" enctype="multipart/form-data">

            <!-- Basic Information -->
            <h6 class="border-bottom pb-2 mb-3">Informações Básicas</h6>
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <div class="mb-2">
                        <?php if (isset($user) && !empty($user['auth_image'])): ?>
                            <img src="<?= base_url('usuarios/exibirFoto/' . $user['id']) ?>"
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
                            <label class="form-label">Usuário (Login) <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required
                                value="<?= old('username', $user['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= old('email', $user['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= isset($user) ? 'Nova Senha (deixe em branco para manter)' : 'Senha' ?> <span class="text-danger"><?= isset($user) ? '' : '*' ?></span></label>
                            <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Senha <span class="text-danger"><?= isset($user) ? '' : '*' ?></span></label>
                            <input type="password" name="pass_confirm" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="active" value="1" id="activeCheck"
                                    <?= (!isset($user) || (isset($user['active']) && $user['active'])) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="activeCheck">Usuário Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Fields / Professional Info -->
            <h6 class="border-bottom pb-2 mb-3 mt-4">Dados Profissionais</h6>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_lawyer" value="1" id="isLawyerCheck"
                            <?= old('is_lawyer', $user['is_lawyer'] ?? 0) ? 'checked' : '' ?>
                            onchange="toggleAdvogadoFields()">
                        <label class="form-check-label" for="isLawyerCheck">É Advogado?</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="rateio_ativo" value="1" id="rateioCheck"
                            <?= old('rateio_ativo', $user['rateio_ativo'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="rateioCheck">Participa de Rateio?</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="advogadoFields" style="display: none;">
                <div class="col-md-6">
                    <label class="form-label">Número OAB</label>
                    <input type="text" name="oab_numero" class="form-control"
                        value="<?= old('oab_numero', $user['oab_numero'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">UF OAB</label>
                    <select name="oab_uf" class="form-select">
                        <option value="">Selecione...</option>
                        <?php
                        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                        foreach ($ufs as $uf): ?>
                            <option value="<?= $uf ?>" <?= (old('oab_uf', $user['oab_uf'] ?? '') == $uf) ? 'selected' : '' ?>><?= $uf ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Groups & Permissions -->
            <?php
            $config = config('AuthGroups');
            $userGroups = $user['groups'] ?? [];
            $userPermissions = $user['permissions'] ?? [];
            ?>

            <h6 class="border-bottom pb-2 mb-3 mt-4">Grupos e Funções</h6>
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
                    // Group permissions loosely by prefix for better UI
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
                <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Usuário
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleAdvogadoFields() {
        const isLawyer = document.getElementById('isLawyerCheck').checked;
        const fields = document.getElementById('advogadoFields');
        fields.style.display = isLawyer ? 'flex' : 'none';
    }

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

    document.addEventListener('DOMContentLoaded', () => {
        toggleAdvogadoFields();
    });
</script>
<?= $this->endSection() ?>