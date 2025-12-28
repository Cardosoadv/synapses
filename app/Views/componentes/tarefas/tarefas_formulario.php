<?php



// Determine current link type
$vinculoTipo = 'processo'; // Default
if (isset($tarefas['cliente_id']) && !empty($tarefas['cliente_id'])) {
    $vinculoTipo = 'cliente';
} elseif (isset($tarefas['caso_id']) && !empty($tarefas['caso_id'])) {
    $vinculoTipo = 'caso';
}
?>
<style>
    /* Estilo de Label - padrão do processos_formulario */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: #6c757d;
    }

    /* Estilo de Botões de Ação */
    .border-top {
        border-color: #dee2e6 !important;
    }
</style>

<form method="post" id="form_tarefa" name="form_tarefa" action="<?= site_url('/tarefas/nova') ?>">
    <input type="hidden" name="id_tarefa" class="form-control" value="<?= $tarefas['id_tarefa'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label for="tarefa" class="form-label">
                <i class="bi bi-check2-circle"></i> Tarefa
            </label>
            <input type="text" name="tarefa" id="tarefa" class="form-control" value="<?= $tarefas['tarefa'] ?? '' ?>">
        </div>
        <div class="form-group col-md-6">
            <label for="prazo" class="form-label">
                <i class="bi bi-calendar-check"></i> Prazo
            </label>
            <input type="date" name="prazo" id="prazo" value="<?= $tarefas['prazo'] ?? '' ?>" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" spellcheck="false" data-ms-editor="true">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col-md-4">
            <label for="vinculo_tipo" class="form-label">
                <i class="bi bi-link-45deg"></i> Vincular a
            </label>
            <select name="vinculo_tipo" id="vinculo_tipo" class="form-control">
                <option value="processo" <?= $vinculoTipo == 'processo' ? 'selected' : '' ?>>Processo</option>
                <option value="cliente" <?= $vinculoTipo == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                <option value="caso" <?= $vinculoTipo == 'caso' ? 'selected' : '' ?>>Caso</option>
            </select>
        </div>

        <div class="form-group col-md-8 position-relative" id="div_processo" style="<?= $vinculoTipo == 'processo' ? '' : 'display:none;' ?>">
            <label for="processo_busca" class="form-label">
                <i class="bi bi-journal-text"></i> Processo Associado
            </label>
            <div class="input-group">
                <input type="text" id="processo_busca" class="form-control" placeholder="Digite o número ou título..." autocomplete="off">
                <input type="hidden" name="processo_id" id="processo_id" value="<?= $tarefas['processo_id'] ?? '' ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="limparSelecao('processo')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="processo_results" class="list-group position-absolute w-100 shadow-sm" style="display:none; z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
        </div>

        <div class="form-group col-md-8 position-relative" id="div_cliente" style="<?= $vinculoTipo == 'cliente' ? '' : 'display:none;' ?>">
            <label for="cliente_busca" class="form-label">
                <i class="bi bi-person-lines-fill"></i> Cliente Associado
            </label>
            <div class="input-group">
                <input type="text" id="cliente_busca" class="form-control" placeholder="Digite o nome ou CPF/CNPJ..." autocomplete="off">
                <input type="hidden" name="cliente_id" id="cliente_id" value="<?= $tarefas['cliente_id'] ?? '' ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="limparSelecao('cliente')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="cliente_results" class="list-group position-absolute w-100 shadow-sm" style="display:none; z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
        </div>

        <div class="form-group col-md-8 position-relative" id="div_caso" style="<?= $vinculoTipo == 'caso' ? '' : 'display:none;' ?>">
            <label for="caso_busca" class="form-label">
                <i class="bi bi-briefcase"></i> Caso Associado
            </label>
            <div class="input-group">
                <input type="text" id="caso_busca" class="form-control" placeholder="Digite o título do caso..." autocomplete="off">
                <input type="hidden" name="caso_id" id="caso_id" value="<?= $tarefas['caso_id'] ?? '' ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="limparSelecao('caso')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="caso_results" class="list-group position-absolute w-100 shadow-sm" style="display:none; z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col-md-4">
            <label for="responsavel" class="form-label">
                <i class="bi bi-person"></i> Responsável
            </label>
            <?php if (isset($responsaveis['users'])) : ?>
                <select name="responsavel" id="responsavel" class="form-control">
                    <option value="">Selecione...</option>
                    <?php foreach ($responsaveis['users'] as $responsavel) : ?>
                        <option value="<?= ($responsavel['id'] ?? '') ?>" <?= ($responsavel['id'] ?? '') == ($tarefas['responsavel'] ?? '') ? 'selected' : '' ?>><?= ($responsavel['username'] ?? '') ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="form-group col-md-4">
            <label for="status" class="form-label">
                <i class="bi bi-info-circle"></i> Status
            </label>
            <select name="status" id="status" class="form-control">
                <option value="1" <?= ($tarefas['status'] ?? '1') == '1' ? 'selected' : '' ?>>Backlog</option>
                <option value="2" <?= ($tarefas['status'] ?? '') == '2' ? 'selected' : '' ?>>A Fazer</option>
                <option value="3" <?= ($tarefas['status'] ?? '') == '3' ? 'selected' : '' ?>>Fazendo</option>
                <option value="4" <?= ($tarefas['status'] ?? '') == '4' ? 'selected' : '' ?>>Feito</option>
                <option value="5" <?= ($tarefas['status'] ?? '') == '5' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="prioridade" class="form-label">
                <i class="bi bi-exclamation-triangle"></i> Prioridade
            </label>
            <select name="prioridade" id="prioridade" class="form-control">
                <option value="1" <?= ($tarefas['prioridade'] ?? '3') == '1' ? 'selected' : '' ?>>Muito Baixa</option>
                <option value="2" <?= ($tarefas['prioridade'] ?? '3') == '2' ? 'selected' : '' ?>>Baixa</option>
                <option value="3" <?= ($tarefas['prioridade'] ?? '3') == '3' ? 'selected' : '' ?>>Média</option>
                <option value="4" <?= ($tarefas['prioridade'] ?? '') == '4' ? 'selected' : '' ?>>Alta</option>
                <option value="5" <?= ($tarefas['prioridade'] ?? '') == '5' ? 'selected' : '' ?>>Muito Alta</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col-12">
            <label for="detalhes" class="form-label">
                <i class="bi bi-text-left"></i> Detalhes
            </label>
            <textarea name="detalhes" id="detalhes" placeholder="Adicione detalhes sobre a tarefa..." class="form-control" rows="4"><?= $tarefas['detalhes'] ?? '' ?></textarea>
        </div>
    </div>

    <div class="mt-4 border-top pt-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Salvar
        </button>
        <a href="<?= site_url('/tarefas/') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vinculoTipoSelect = document.getElementById('vinculo_tipo');
        const divProcesso = document.getElementById('div_processo');
        const divCliente = document.getElementById('div_cliente');
        const divCaso = document.getElementById('div_caso');

        const base_url = '<?= base_url() ?>';

        // Função para atualizar visibilidade dos campos
        function updateVisibility() {
            const tipo = vinculoTipoSelect.value;

            divProcesso.style.display = 'none';
            divCliente.style.display = 'none';
            divCaso.style.display = 'none';

            if (tipo === 'processo') {
                divProcesso.style.display = 'block';
            } else if (tipo === 'cliente') {
                divCliente.style.display = 'block';
            } else if (tipo === 'caso') {
                divCaso.style.display = 'block';
            }
        }

        vinculoTipoSelect.addEventListener('change', updateVisibility);

        // Funções para busca dinâmica
        function setupSearch(type, endpoint, formatResult) {
            const input = document.getElementById(`${type}_busca`);
            const resultsDiv = document.getElementById(`${type}_results`);
            const hiddenId = document.getElementById(`${type}_id`);
            let timeout = null;

            if (!input) return;

            input.addEventListener('input', function() {
                const term = this.value;
                clearTimeout(timeout);

                if (term.length < 3) {
                    resultsDiv.style.display = 'none';
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`${base_url}${endpoint}/${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(resp => {
                            let data = [];
                            console.log(`[${type}] Raw Response:`, resp);

                            // Normalização da resposta
                            if (Array.isArray(resp)) {
                                data = resp;
                            } else if (resp.results && Array.isArray(resp.results)) {
                                data = resp.results;
                            } else if (resp.data && Array.isArray(resp.data)) {
                                // Caso Processos: { success: true, data: [...] }
                                data = resp.data;
                            } else {
                                // Caso fallback: chaves numéricas
                                Object.keys(resp).forEach(key => {
                                    if (!isNaN(parseInt(key))) {
                                        data.push(resp[key]);
                                    }
                                });
                            }

                            console.log(`[${type}] Processed Data:`, data);

                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const a = document.createElement('a');
                                    a.href = '#';
                                    a.className = 'list-group-item list-group-item-action';
                                    a.innerHTML = formatResult(item);

                                    // Usar mousedown
                                    a.addEventListener('mousedown', (e) => {
                                        e.preventDefault();
                                        input.value = a.innerText.trim();

                                        // Extração de ID melhorada
                                        let selectedId = item.id_processo || item.id || item[`id_${type}`];

                                        console.log(`[${type}] Selected ID:`, selectedId);

                                        if (selectedId) {
                                            hiddenId.value = selectedId;
                                            resultsDiv.style.display = 'none';
                                        } else {
                                            console.warn('ID não encontrado para o item selecionado', item);
                                        }
                                    });

                                    resultsDiv.appendChild(a);
                                });
                                resultsDiv.style.display = 'block';
                            } else {
                                resultsDiv.innerHTML = '<div class="list-group-item">Nenhum resultado encontrado</div>';
                                resultsDiv.style.display = 'block';
                            }
                        })
                        .catch(err => {
                            console.error('Erro na busca:', err);
                            resultsDiv.style.display = 'none';
                        });
                }, 400);
            });

            // Fechar ao clicar fora
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
                    resultsDiv.style.display = 'none';
                }
            });

            // Input blur
            input.addEventListener('blur', () => {
                setTimeout(() => {
                    if (resultsDiv.style.display === 'block') {
                        resultsDiv.style.display = 'none';
                    }
                }, 200);
            });
        }

        // Expor função de limpar globalmente
        window.limparSelecao = function(type) {
            document.getElementById(`${type}_busca`).value = '';
            document.getElementById(`${type}_id`).value = '';
            document.getElementById(`${type}_results`).style.display = 'none';
        };

        // Inicializar buscas
        // Processos: ajustado para chaves numero_processo e nome conforme retorno da API
        setupSearch('processo', 'processos/buscar', (item) => {
            return `${item.numero_processo} - ${item.nome || 'Sem título'}`;
        });

        // Clientes e Casos: retornam {id, text}
        setupSearch('cliente', 'clientes/buscar', (item) => item.text);
        setupSearch('caso', 'casos/buscar', (item) => item.text);
    });
</script>