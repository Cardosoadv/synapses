<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i><?= esc($titulo) ?></h5>
    </div>
    <div class="card-body">
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
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

        <form action="<?= $action ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= old('nome', $item['nome'] ?? '') ?>" required minlength="3">
            </div>

            <div class="mb-3">
                <label for="fundamento_legal" class="form-label">Fundamento Legal</label>
                <textarea class="form-control" id="fundamento_legal" name="fundamento_legal" rows="3"><?= old('fundamento_legal', $item['fundamento_legal'] ?? '') ?></textarea>
                <div class="form-text">Informe a lei, decreto ou normativa que fundamenta este registro.</div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url($controller) ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
