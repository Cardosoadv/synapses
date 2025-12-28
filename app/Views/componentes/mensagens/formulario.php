<form method="post" id="form_mensagens" name="form_mensaens" action="<?= site_url('mensagens/salvar') ?>">
    <input type="hidden" name="id" value="<?= $mensagem['id'] ?? '' ?>">

    <div class="row mb-3">

        <div class="form-group col">
            <label for="destinatario_id">Destinatário</label>
            <select name="destinatario_id" class="form-control" required>
                <option value="">Selecione um Destinatário</option>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= isset($destinatario_id) && $destinatario_id == $user['id'] ? 'selected' : '' ?>>
                            <?= esc($user['username']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>


    <div class="row mb-3">
        <div class="form-group col">
            <label for="assunto">Assunto</label>
            <input type="text" class="form-control" name="assunto" id="assunto" value="<?= $mensagem['assunto'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="conteudo">Conteúdo</label>
            <textarea class="form-control" name="conteudo" id="conteudo"><?= $mensagem['conteudo'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('mensagens/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>