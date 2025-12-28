let fornecedorSelecionado = null;

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

const baseUrl = AppConfig.baseUrl; // Ensure AppConfig is available or use variable if passed

function buscarFornecedores(termo) {
    if (!termo || termo.length < 2) {
        limparResultadosFornecedor();
        return;
    }

    fetch(`${baseUrl}fornecedores/buscar?term=${encodeURIComponent(termo)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.results) {
                renderFornecedorResults(data.results);
            } else {
                renderFornecedorResults([]);
            }
        })
        .catch(error => {
            console.error('Erro ao buscar fornecedores:', error);
            renderFornecedorResults([]);
        });
}

function renderFornecedorResults(fornecedores) {
    const container = document.getElementById('fornecedor-search-results');
    if (!container) return;

    if (!fornecedores || fornecedores.length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted small">Nenhum fornecedor encontrado</div>';
        container.style.display = 'block';
        return;
    }

    container.innerHTML = fornecedores.map(fornecedor => `
                <a href="#" class="list-group-item list-group-item-action p-2" 
                   data-fornecedor-id="${fornecedor.id}" 
                   data-fornecedor-text="${fornecedor.text}">
                    <small><strong>${fornecedor.text}</strong></small>
                </a>
            `).join('');

    container.style.display = 'block';

    container.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            selecionarFornecedor({
                id: this.dataset.fornecedorId,
                text: this.dataset.fornecedorText
            });
        });
    });
}

function selecionarFornecedor(fornecedor) {
    fornecedorSelecionado = fornecedor;

    const campoBusca = document.getElementById('fornecedor_busca');
    const searchResults = document.getElementById('fornecedor-search-results');
    const inputFornecedorId = document.getElementById('fornecedor_id');
    const btnBuscar = document.getElementById('btn-buscar-fornecedor');

    if (!campoBusca) return;

    if (inputFornecedorId) {
        inputFornecedorId.value = fornecedor.id;
    }

    campoBusca.value = fornecedor.text;

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

function limparSelecaoFornecedor() {
    const campoBusca = document.getElementById('fornecedor_busca');
    const searchResults = document.getElementById('fornecedor-search-results');
    const inputFornecedorId = document.getElementById('fornecedor_id');
    const btnBuscar = document.getElementById('btn-buscar-fornecedor');

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

    if (inputFornecedorId) {
        inputFornecedorId.value = '';
    }

    if (btnBuscar) {
        btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
        btnBuscar.title = 'Buscar fornecedor';
    }

    fornecedorSelecionado = null;
}

function limparResultadosFornecedor() {
    const container = document.getElementById('fornecedor-search-results');
    if (container) {
        container.style.display = 'none';
        container.innerHTML = '';
    }
}

// Inicialização dos listeners de fornecedor
document.addEventListener('DOMContentLoaded', function () {
    const campoBuscaFornecedor = document.getElementById('fornecedor_busca');
    const btnBuscarFornecedor = document.getElementById('btn-buscar-fornecedor');
    const searchResultsFornecedor = document.getElementById('fornecedor-search-results');

    if (campoBuscaFornecedor) {
        // Verifica se já tem fornecedor selecionado ao carregar
        if (document.getElementById('fornecedor_id') && document.getElementById('fornecedor_id').value) {
            campoBuscaFornecedor.readOnly = true;
            campoBuscaFornecedor.classList.add('bg-light');
            if (btnBuscarFornecedor) {
                btnBuscarFornecedor.innerHTML = '<i class="bi bi-x-circle"></i>';
                btnBuscarFornecedor.title = 'Limpar seleção';
            }
        }

        const debouncedSearchFornecedor = debounce(function (termo) {
            buscarFornecedores(termo);
        }, 300);

        campoBuscaFornecedor.addEventListener('input', function (e) {
            const termo = e.target.value.trim();

            if (this.readOnly) return;

            if (termo.length >= 2) {
                debouncedSearchFornecedor(termo);
            } else {
                limparResultadosFornecedor();
            }
        });

        if (btnBuscarFornecedor) {
            btnBuscarFornecedor.addEventListener('click', function (e) {
                e.preventDefault();

                if (document.getElementById('fornecedor_id') && document.getElementById('fornecedor_id').value) {
                    limparSelecaoFornecedor();
                } else {
                    const termo = campoBuscaFornecedor.value.trim();
                    if (termo.length >= 2) {
                        buscarFornecedores(termo);
                    }
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (!searchResultsFornecedor) return;

            if (!campoBuscaFornecedor.contains(e.target) &&
                !searchResultsFornecedor.contains(e.target) &&
                !btnBuscarFornecedor?.contains(e.target)) {
                limparResultadosFornecedor();
            }
        });

        // Atalho Ctrl+K atualizado para focar no novo campo
        document.addEventListener('keydown', function (e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                if (!campoBuscaFornecedor.readOnly) {
                    campoBuscaFornecedor.focus();
                }
            }
        });
    }
});
