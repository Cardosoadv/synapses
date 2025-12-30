<?= $this->extend('template/layout') ?>

<?= $this->section('header') ?>
<title><?= esc($titulo) ?> - Synapses</title>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-link-45deg me-2"></i><?= esc($titulo) ?></h5>
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

        <form action="<?= base_url('empresas/salvarVinculo') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="empresa_id" name="empresa_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($empresas as $empresa): ?>
                            <option value="<?= $empresa['id'] ?>" <?= old('empresa_id') == $empresa['id'] ? 'selected' : '' ?>>
                                <?= esc($empresa['razao_social']) ?> (<?= esc($empresa['cnpj']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="profissional_id" class="form-label">Profissional <span class="text-danger">*</span></label>
                    <select class="form-select select2" id="profissional_id" name="profissional_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($profissionais as $prof): ?>
                            <option value="<?= $prof['id'] ?>" <?= old('profissional_id') == $prof['id'] ? 'selected' : '' ?>>
                                <?= esc($prof['name']) ?> (CPF: <?= esc($prof['cpf']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="motivo_id" class="form-label">Motivação <span class="text-danger">*</span></label>
                    <select class="form-select" id="motivo_id" name="motivo_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($motivos as $motivo): ?>
                            <option value="<?= $motivo['id'] ?>" <?= old('motivo_id') == $motivo['id'] ? 'selected' : '' ?>>
                                <?= esc($motivo['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="data_inicio" class="form-label">Data Início <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= old('data_inicio') ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= old('data_fim') ?>">
                </div>

                <div class="col-12">
                    <label for="comentario" class="form-label">Comentário/Observação <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="comentario" name="comentario" rows="3" required><?= old('comentario') ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="<?= base_url('empresas') ?>" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-link-45deg me-1"></i> Vincular</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Assuming Select2 is available as per standard templates, if not it will fallback to normal select
    document.addEventListener('DOMContentLoaded', function() {
        // Placeholder for select2 initialization if needed on the page specifically
        // $( '.select2' ).select2( { theme: "bootstrap-5" } );
    });
</script>
<?= $this->endSection() ?>