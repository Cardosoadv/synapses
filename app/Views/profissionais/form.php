<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-badge me-2"></i>
            <?= isset($profissional) ? 'Editar Profissional' : 'Novo Profissional' ?>
        </h5>
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

        <form action="<?= isset($profissional) ? base_url('profissionais/update/' . $profissional['id']) : base_url('profissionais/create') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <ul class="nav nav-tabs mb-3" id="professionalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dados-tab" data-bs-toggle="tab" data-bs-target="#dados" type="button" role="tab">Dados Pessoais</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="endereco-tab" data-bs-toggle="tab" data-bs-target="#endereco" type="button" role="tab">Endereço</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="atribuicoes-tab" data-bs-toggle="tab" data-bs-target="#atribuicoes" type="button" role="tab">Atribuições</button>
                </li>
            </ul>

            <div class="tab-content" id="professionalTabsContent">
                <!-- DADOS PESSOAIS -->
                <div class="tab-pane fade show active" id="dados" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $profissional['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="cpf" class="form-label">CPF *</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" value="<?= old('cpf', $profissional['cpf'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="registration_number" class="form-label">Registro Profissional</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= old('registration_number', $profissional['registration_number'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="sei_process" class="form-label">Processo SEI</label>
                            <input type="text" class="form-control" id="sei_process" name="sei_process" value="<?= old('sei_process', $profissional['sei_process'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= (old('status', $profissional['status'] ?? '') == 'active') ? 'selected' : '' ?>>Ativo</option>
                                <option value="inactive" <?= (old('status', $profissional['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inativo</option>
                                <option value="suspended" <?= (old('status', $profissional['status'] ?? '') == 'suspended') ? 'selected' : '' ?>>Suspenso</option>
                                <option value="deceased" <?= (old('status', $profissional['status'] ?? '') == 'deceased') ? 'selected' : '' ?>>Falecido</option>
                                <option value="pending" <?= (old('status', $profissional['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Pendente</option>
                            </select>
                        </div>
                         <!-- Optional User Link -->
                         <div class="col-md-4">
                            <label for="user_id" class="form-label">ID Usuário Vinculado (Opcional)</label>
                            <input type="number" class="form-control" id="user_id" name="user_id" value="<?= old('user_id', $profissional['user_id'] ?? '') ?>">
                            <small class="text-muted">Integração com tabela users.</small>
                        </div>
                    </div>
                </div>

                <!-- ENDEREÇO -->
                <div class="tab-pane fade" id="endereco" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="<?= old('cep', $profissional['endereco']['cep'] ?? '') ?>" onblur="buscarCep(this.value)">
                        </div>
                        <div class="col-md-7">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= old('logradouro', $profissional['endereco']['logradouro'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= old('numero', $profissional['endereco']['numero'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" value="<?= old('complemento', $profissional['endereco']['complemento'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?= old('bairro', $profissional['endereco']['bairro'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" value="<?= old('cidade', $profissional['endereco']['cidade'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="uf" class="form-label">UF</label>
                            <input type="text" class="form-control" id="uf" name="uf" value="<?= old('uf', $profissional['endereco']['uf'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- ATRIBUIÇÕES -->
                <div class="tab-pane fade" id="atribuicoes" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Profissões</label>
                            <div class="card card-body" style="max-height: 200px; overflow-y: auto;">
                                <?php 
                                $selectedProfissoes = [];
                                if (isset($profissional['profissoes'])) {
                                    $selectedProfissoes = array_column($profissional['profissoes'], 'profissao_id');
                                } elseif (old('profissoes')) {
                                    $selectedProfissoes = old('profissoes');
                                }
                                ?>
                                <?php foreach ($profissoes as $p): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="profissoes[]" value="<?= $p['id'] ?>" id="prof_<?= $p['id'] ?>" 
                                            <?= in_array($p['id'], $selectedProfissoes) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="prof_<?= $p['id'] ?>">
                                            <?= esc($p['nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Categorias</label>
                            <div class="card card-body" style="max-height: 200px; overflow-y: auto;">
                                <?php 
                                $selectedCategorias = [];
                                if (isset($profissional['categorias'])) {
                                    $selectedCategorias = array_column($profissional['categorias'], 'categoria_id');
                                } elseif (old('categorias')) {
                                    $selectedCategorias = old('categorias');
                                }
                                ?>
                                <?php foreach ($categorias as $c): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categorias[]" value="<?= $c['id'] ?>" id="cat_<?= $c['id'] ?>"
                                            <?= in_array($c['id'], $selectedCategorias) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="cat_<?= $c['id'] ?>">
                                            <?= esc($c['nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Atribuições</label>
                            <div class="card card-body" style="max-height: 200px; overflow-y: auto;">
                                <?php 
                                $selectedAtribuicoes = [];
                                if (isset($profissional['atribuicoes'])) {
                                    $selectedAtribuicoes = array_column($profissional['atribuicoes'], 'atribuicao_id');
                                } elseif (old('atribuicoes')) {
                                    $selectedAtribuicoes = old('atribuicoes');
                                }
                                ?>
                                <?php foreach ($atribuicoes as $a): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="atribuicoes[]" value="<?= $a['id'] ?>" id="attr_<?= $a['id'] ?>"
                                            <?= in_array($a['id'], $selectedAtribuicoes) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="attr_<?= $a['id'] ?>">
                                            <?= esc($a['nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4 text-end">
                <a href="<?= base_url('profissionais') ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function buscarCep(cep) {
        cep = cep.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                        document.getElementById('numero').focus();
                    }
                })
                .catch(error => console.error('Erro ao buscar CEP:', error));
        }
    }
</script>

<?= $this->endSection() ?>
