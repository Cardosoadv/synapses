<?= $this->extend('template/layout') ?>

<?= $this->section('header') ?>
<title><?= esc($titulo) ?> - Synapses</title>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i><?= esc($titulo) ?></h5>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php
        $action = isset($empresa) ? 'empresas/atualizar/' . $empresa['id'] : 'empresas/salvar';
        ?>

        <form action="<?= base_url($action) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="razao_social" class="form-label">Razão Social <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="razao_social" name="razao_social" value="<?= old('razao_social', $empresa['razao_social'] ?? '') ?>" required minlength="3" maxlength="255">
                </div>

                <div class="col-md-6">
                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" value="<?= old('nome_fantasia', $empresa['nome_fantasia'] ?? '') ?>" maxlength="255">
                </div>

                <div class="col-md-4">
                    <label for="cnpj" class="form-label">CNPJ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= old('cnpj', $empresa['cnpj'] ?? '') ?>" required placeholder="00.000.000/0000-00">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="<?= base_url('empresas') ?>" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple CNPJ Mask
        const cnpjInput = document.getElementById('cnpj');
        if (cnpjInput) {
            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 14) value = value.slice(0, 14);

                if (value.length > 12) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
                } else if (value.length > 8) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4}).*/, '$1.$2.$3/$4');
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{0,3}).*/, '$1.$2.$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{0,3}).*/, '$1.$2');
                }
                e.target.value = value;
            });
        }
    });
</script>
<?= $this->endSection() ?>