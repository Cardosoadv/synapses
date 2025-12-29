<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-up"></i> Promover Usuário a Funcionário
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Você está promovendo o usuário <strong><?= esc($user['username']) ?></strong> (<?= esc($user['email']) ?>).
            Ele passará a ter um registro de funcionário vinculado.
        </div>

        <form action="<?= base_url('usuarios/salvarPromocao/' . $user['id']) ?>" method="post">
            
            <h6 class="border-bottom pb-2 mb-3">Dados do Funcionário</h6>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" required
                        value="<?= old('cpf') ?>" placeholder="000.000.000-00">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Matrícula <span class="text-danger">*</span></label>
                    <input type="text" name="registration_number" class="form-control" required
                        value="<?= old('registration_number') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control" required
                        value="<?= old('position') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="department" class="form-control"
                        value="<?= old('department') ?>">
                </div>
            </div>

            <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Confirmar Promoção
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
