// ===== BUSCA DE PROCESSO =====
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AppConfig === 'undefined') {
        console.error('AppConfig não definido. Verifique o layout.');
        return;
    }
    const baseUrl = AppConfig.baseUrl;

    let processoSelecionado = null;

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function buscarProcessos(termo) {
        if (!termo || termo.length < 3) {
            limparResultadosProcesso();
            return;
        }

        fetch(`${baseUrl}processos/buscar/${encodeURIComponent(termo)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Endpoint original retornava {success:true, data: [...]}
                if (data.success && data.data) {
                    renderProcessoResults(data.data);
                } else {
                    renderProcessoResults([]);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar processos:', error);
                renderProcessoResults([]);
            });
    }

    function renderProcessoResults(processos) {
        const container = document.getElementById('processo-search-results');
        if (!container) return;

        if (!processos || processos.length === 0) {
            container.innerHTML = '<div class="list-group-item text-muted small">Nenhum processo encontrado</div>';
            container.style.display = 'block';
            return;
        }

        container.innerHTML = processos.map(processo => `
                <a href="#" class="list-group-item list-group-item-action p-2" 
                   data-processo-id="${processo.id_processo}" 
                   data-processo-numero="${processo.numero_processo}"
                   data-processo-titulo="${processo.titulo_processo || processo.nome || ''}"> <!-- Adicionei fallback para nome -->
                    <small>
                        <strong>${processo.numero_processo}</strong><br>
                        <span class="text-muted">${processo.titulo_processo || processo.nome || 'Sem descrição'}</span>
                    </small>
                </a>
            `).join('');

        container.style.display = 'block';

        container.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selecionarProcesso({
                    id: this.dataset.processoId,
                    numero: this.dataset.processoNumero, // Usando 'numero' para padronizar com o objeto esperado
                    text: this.dataset.processoNumero // Usando 'text' para padronizar com input
                });
            });
        });
    }

    function selecionarProcesso(processo) {
        processoSelecionado = processo;

        const campoBusca = document.getElementById('numero_processo_busca');
        const searchResults = document.getElementById('processo-search-results');
        const inputProcessoId = document.getElementById('processo_id');
        const btnBuscar = document.getElementById('btn-buscar-processo');

        if (!campoBusca) return;

        if (inputProcessoId) {
            inputProcessoId.value = processo.id;
        }

        campoBusca.value = processo.numero || processo.text;

        if (searchResults) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
        }

        campoBusca.readOnly = true;
        campoBusca.classList.add('bg-light');

        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-x-circle"></i>';
            btnBuscar.title = 'Limpar seleção';
        }
    }

    function limparSelecaoProcesso() {
        const campoBusca = document.getElementById('numero_processo_busca');
        const searchResults = document.getElementById('processo-search-results');
        const inputProcessoId = document.getElementById('processo_id');
        const btnBuscar = document.getElementById('btn-buscar-processo');

        if (campoBusca) {
            campoBusca.readOnly = false;
            campoBusca.classList.remove('bg-light');
            campoBusca.value = '';
            campoBusca.focus();
        }

        if (searchResults) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
        }

        if (inputProcessoId) {
            inputProcessoId.value = '';
        }

        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
            btnBuscar.title = 'Buscar processo';
        }

        processoSelecionado = null;
    }

    function limparResultadosProcesso() {
        const container = document.getElementById('processo-search-results');
        if (container) {
            container.style.display = 'none';
            container.innerHTML = '';
        }
    }

    // Event listeners
    const campoBuscaProcesso = document.getElementById('numero_processo_busca');
    const btnBuscarProcesso = document.getElementById('btn-buscar-processo');
    const searchResultsProcesso = document.getElementById('processo-search-results');

    if (campoBuscaProcesso) {
        const debouncedSearch = debounce(function (termo) {
            buscarProcessos(termo);
        }, 300);

        campoBuscaProcesso.addEventListener('input', function (e) {
            const termo = e.target.value.trim();

            if (this.readOnly) return;

            if (termo.length >= 3) { // Processos geralmente precisam de mais caracteres
                debouncedSearch(termo);
            } else {
                limparResultadosProcesso();
            }
        });

        if (btnBuscarProcesso) {
            btnBuscarProcesso.addEventListener('click', function (e) {
                e.preventDefault();

                if (document.getElementById('processo_id') && document.getElementById('processo_id').value) {
                    limparSelecaoProcesso();
                } else {
                    const termo = campoBuscaProcesso.value.trim();
                    if (termo.length >= 3) {
                        buscarProcessos(termo);
                    }
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (!searchResultsProcesso) return;

            if (!campoBuscaProcesso.contains(e.target) &&
                !searchResultsProcesso.contains(e.target) &&
                !btnBuscarProcesso?.contains(e.target)) {
                limparResultadosProcesso();
            }
        });

        // Modal reset logic
        const modalVincularProcesso = document.getElementById('modalVincularProcesso');
        if (modalVincularProcesso) {
            modalVincularProcesso.addEventListener('hidden.bs.modal', function () {
                limparSelecaoProcesso();
            });
        }
    }
});