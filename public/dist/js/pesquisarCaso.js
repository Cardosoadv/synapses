// ===== BUSCA DE CASO =====
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AppConfig === 'undefined') {
        console.error('AppConfig não definido. Verifique o layout.');
        return;
    }
    const baseUrl = AppConfig.baseUrl;

    let casoSelecionado = null;

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

    function buscarCasos(termo) {
        if (!termo || termo.length < 2) {
            limparResultadosCaso();
            return;
        }

        fetch(`${baseUrl}casos/buscar?term=${encodeURIComponent(termo)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.results) {
                    renderCasoResults(data.results);
                } else {
                    renderCasoResults([]);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar casos:', error);
                renderCasoResults([]);
            });
    }

    function renderCasoResults(casos) {
        const container = document.getElementById('caso-search-results');
        if (!container) return;

        if (!casos || casos.length === 0) {
            container.innerHTML = '<div class="list-group-item text-muted small">Nenhum caso encontrado</div>';
            container.style.display = 'block';
            return;
        }

        container.innerHTML = casos.map(caso => `
                <a href="#" class="list-group-item list-group-item-action p-2" 
                   data-caso-id="${caso.id}" 
                   data-caso-text="${caso.text}">
                    <small><strong>${caso.text}</strong></small>
                </a>
            `).join('');

        container.style.display = 'block';

        container.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selecionarCaso({
                    id: this.dataset.casoId,
                    text: this.dataset.casoText
                });
            });
        });
    }

    function selecionarCaso(caso) {
        casoSelecionado = caso;

        const campoBusca = document.getElementById('caso_busca');
        const searchResults = document.getElementById('caso-search-results');
        const inputCasoId = document.getElementById('caso_id');

        if (!campoBusca) return;

        if (inputCasoId) {
            inputCasoId.value = caso.id;
        }

        campoBusca.value = caso.text;

        if (searchResults) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
        }

        campoBusca.readOnly = true;
        campoBusca.classList.add('bg-light');

        const btnBuscar = document.getElementById('btn-buscar-caso');
        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-x-circle"></i>';
            btnBuscar.title = 'Limpar seleção';
        }
    }

    function limparSelecaoCaso() {
        const campoBusca = document.getElementById('caso_busca');
        const searchResults = document.getElementById('caso-search-results');
        const inputCasoId = document.getElementById('caso_id');
        const btnBuscar = document.getElementById('btn-buscar-caso');

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

        if (inputCasoId) {
            inputCasoId.value = '';
        }

        if (btnBuscar) {
            btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
            btnBuscar.title = 'Buscar caso';
        }

        casoSelecionado = null;
    }

    function limparResultadosCaso() {
        const container = document.getElementById('caso-search-results');
        if (container) {
            container.style.display = 'none';
            container.innerHTML = '';
        }
    }

    // Event listeners
    const campoBuscaCaso = document.getElementById('caso_busca');
    const btnBuscarCaso = document.getElementById('btn-buscar-caso');
    const searchResultsCaso = document.getElementById('caso-search-results');

    if (campoBuscaCaso) {
        // Verifica se já tem ID
        if (document.getElementById('caso_id') && document.getElementById('caso_id').value) {
            campoBuscaCaso.readOnly = true;
            campoBuscaCaso.classList.add('bg-light');
            if (btnBuscarCaso) {
                btnBuscarCaso.innerHTML = '<i class="bi bi-x-circle"></i>';
                btnBuscarCaso.title = 'Limpar seleção';
            }
        }

        const debouncedSearch = debounce(function (termo) {
            buscarCasos(termo);
        }, 300);

        campoBuscaCaso.addEventListener('input', function (e) {
            const termo = e.target.value.trim();

            if (this.readOnly) return;

            if (termo.length >= 2) {
                debouncedSearch(termo);
            } else {
                limparResultadosCaso();
            }
        });

        if (btnBuscarCaso) {
            btnBuscarCaso.addEventListener('click', function (e) {
                e.preventDefault();

                if (document.getElementById('caso_id') && document.getElementById('caso_id').value) {
                    limparSelecaoCaso();
                } else {
                    const termo = campoBuscaCaso.value.trim();
                    if (termo.length >= 2) {
                        buscarCasos(termo);
                    }
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (!searchResultsCaso) return;

            if (!campoBuscaCaso.contains(e.target) &&
                !searchResultsCaso.contains(e.target) &&
                !btnBuscarCaso?.contains(e.target)) {
                limparResultadosCaso();
            }
        });

        // Modal reset logic - important because these inputs are inside Bootstrap modals
        const modalVincularCaso = document.getElementById('modalVincularCaso');
        if (modalVincularCaso) {
            modalVincularCaso.addEventListener('hidden.bs.modal', function () {
                limparSelecaoCaso();
            });
        }
    }
});
