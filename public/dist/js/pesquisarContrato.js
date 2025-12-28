// ===== BUSCA DE CONTRATO =====
document.addEventListener('DOMContentLoaded', function () {
    let contratoSelecionado = null;
    if (typeof AppConfig === 'undefined') {
        console.error('AppConfig não definido. Verifique o layout.');
        return;
    }
    const baseUrl = AppConfig.baseUrl;

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

    function buscarContratos(termo) {
        if (!termo || termo.length < 2) {
            limparResultadosContrato();
            return;
        }

        fetch(`${baseUrl}contratos/buscar?term=${encodeURIComponent(termo)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.results) {
                    renderContratoResults(data.results);
                } else {
                    renderContratoResults([]);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar contratos:', error);
                renderContratoResults([]);
            });
    }

    function renderContratoResults(contratos) {
        const container = document.getElementById('contrato-search-results');
        if (!container) return;

        if (!contratos || contratos.length === 0) {
            container.innerHTML = '<div class="list-group-item text-muted small">Nenhum contrato encontrado</div>';
            container.style.display = 'block';
            return;
        }

        container.innerHTML = contratos.map(contrato => `
                <a href="#" class="list-group-item list-group-item-action p-2" 
                   data-contrato-id="${contrato.id}" 
                   data-contrato-text="${contrato.text}">
                    <small><strong>${contrato.text}</strong></small>
                </a>
            `).join('');

        container.style.display = 'block';

        container.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selecionarContrato({
                    id: this.dataset.contratoId,
                    text: this.dataset.contratoText
                });
            });
        });
    }

    function selecionarContrato(contrato) {
        contratoSelecionado = contrato;

        const campoBusca = document.getElementById('contrato_busca');
        const searchResults = document.getElementById('contrato-search-results');
        const inputContratoId = document.getElementById('contrato_id');

        if (!campoBusca) return;

        if (inputContratoId) {
            inputContratoId.value = contrato.id;
        }

        campoBusca.value = contrato.text;

        if (searchResults) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
        }

        campoBusca.readOnly = true;
        campoBusca.classList.add('bg-light');

        const btnBuscar = document.getElementById('btn-buscar-contrato');
        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-x-circle"></i>';
            btnBuscar.title = 'Limpar seleção';
        }
    }

    function limparSelecaoContrato() {
        const campoBusca = document.getElementById('contrato_busca');
        const searchResults = document.getElementById('contrato-search-results');
        const inputContratoId = document.getElementById('contrato_id');
        const btnBuscar = document.getElementById('btn-buscar-contrato');

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

        if (inputContratoId) {
            inputContratoId.value = '';
        }

        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
            btnBuscar.title = 'Buscar contrato';
        }

        contratoSelecionado = null;
    }

    function limparResultadosContrato() {
        const container = document.getElementById('contrato-search-results');
        if (container) {
            container.style.display = 'none';
            container.innerHTML = '';
        }
    }

    // Event listeners para busca de contrato
    const campoBuscaContrato = document.getElementById('contrato_busca');
    const btnBuscarContrato = document.getElementById('btn-buscar-contrato');
    const searchResultsContrato = document.getElementById('contrato-search-results');

    if (campoBuscaContrato) {
        // Verifica se já tem contrato selecionado ao carregar
        if (document.getElementById('contrato_id').value) {
            campoBuscaContrato.readOnly = true;
            campoBuscaContrato.classList.add('bg-light');
            if (btnBuscarContrato) {
                btnBuscarContrato.innerHTML = '<i class="bi bi-x-circle"></i>';
                btnBuscarContrato.title = 'Limpar seleção';
            }
        }

        const debouncedSearch = debounce(function (termo) {
            buscarContratos(termo);
        }, 300);

        campoBuscaContrato.addEventListener('input', function (e) {
            const termo = e.target.value.trim();

            if (this.readOnly) return;

            if (termo.length >= 2) {
                debouncedSearch(termo);
            } else {
                limparResultadosContrato();
            }
        });

        if (btnBuscarContrato) {
            btnBuscarContrato.addEventListener('click', function (e) {
                e.preventDefault();

                if (document.getElementById('contrato_id').value) {
                    limparSelecaoContrato();
                } else {
                    const termo = campoBuscaContrato.value.trim();
                    if (termo.length >= 2) {
                        buscarContratos(termo);
                    }
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (!searchResultsContrato) return;

            if (!campoBuscaContrato.contains(e.target) &&
                !searchResultsContrato.contains(e.target) &&
                !btnBuscarContrato?.contains(e.target)) {
                limparResultadosContrato();
            }
        });

        campoBuscaContrato.addEventListener('keydown', function (e) {
            if (!searchResultsContrato || searchResultsContrato.style.display === 'none') return;

            const items = searchResultsContrato.querySelectorAll('a');
            if (items.length === 0) return;

            let currentIndex = Array.from(items).findIndex(item =>
                item.classList.contains('active')
            );

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < items.length - 1) {
                        if (currentIndex >= 0) items[currentIndex].classList.remove('active');
                        items[currentIndex + 1].classList.add('active');
                        items[currentIndex + 1].scrollIntoView({
                            block: 'nearest'
                        });
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        items[currentIndex].classList.remove('active');
                        items[currentIndex - 1].classList.add('active');
                        items[currentIndex - 1].scrollIntoView({
                            block: 'nearest'
                        });
                    }
                    break;

                case 'Enter':
                    e.preventDefault();
                    if (currentIndex >= 0) {
                        items[currentIndex].click();
                    }
                    break;

                case 'Escape':
                    limparResultadosContrato();
                    break;
            }
        });
    }
});
