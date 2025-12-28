let clienteSelecionado = null;

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

const baseUrl = AppConfig.baseUrl;

function buscarClientes(termo) {
    if (!termo || termo.length < 2) {
        limparResultadosCliente();
        return;
    }

    fetch(`${baseUrl}clientes/buscar?term=${encodeURIComponent(termo)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.results) {
                renderClienteResults(data.results);
            } else {
                renderClienteResults([]);
            }
        })
        .catch(error => {
            console.error('Erro ao buscar clientes:', error);
            renderClienteResults([]);
        });
}

function renderClienteResults(clientes) {
    const container = document.getElementById('cliente-search-results');
    if (!container) return;

    if (!clientes || clientes.length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted small">Nenhum cliente encontrado</div>';
        container.style.display = 'block';
        return;
    }

    container.innerHTML = clientes.map(cliente => `
                <a href="#" class="list-group-item list-group-item-action p-2" 
                   data-cliente-id="${cliente.id}" 
                   data-cliente-text="${cliente.text}">
                    <small><strong>${cliente.text}</strong></small>
                </a>
            `).join('');

    container.style.display = 'block';

    container.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            selecionarCliente({
                id: this.dataset.clienteId,
                text: this.dataset.clienteText
            });
        });
    });
}

function selecionarCliente(cliente) {
    clienteSelecionado = cliente;

    const campoBusca = document.getElementById('cliente_busca');
    const searchResults = document.getElementById('cliente-search-results');
    const inputClienteId = document.getElementById('cliente_id');
    const btnBuscar = document.getElementById('btn-buscar-cliente');

    if (!campoBusca) return;

    if (inputClienteId) {
        inputClienteId.value = cliente.id;
    }

    campoBusca.value = cliente.text;

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

function limparSelecaoCliente() {
    const campoBusca = document.getElementById('cliente_busca');
    const searchResults = document.getElementById('cliente-search-results');
    const inputClienteId = document.getElementById('cliente_id');
    const btnBuscar = document.getElementById('btn-buscar-cliente');

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

    if (inputClienteId) {
        inputClienteId.value = '';
    }

    if (btnBuscar) {
        btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
        btnBuscar.title = 'Buscar cliente';
    }

    clienteSelecionado = null;
}

function limparResultadosCliente() {
    const container = document.getElementById('cliente-search-results');
    if (container) {
        container.style.display = 'none';
        container.innerHTML = '';
    }
}

// Inicialização dos listeners de cliente
const campoBuscaCliente = document.getElementById('cliente_busca');
const btnBuscarCliente = document.getElementById('btn-buscar-cliente');
const searchResultsCliente = document.getElementById('cliente-search-results');

if (campoBuscaCliente) {
    // Verifica se já tem cliente selecionado ao carregar
    if (document.getElementById('cliente_id') && document.getElementById('cliente_id').value) {
        campoBuscaCliente.readOnly = true;
        campoBuscaCliente.classList.add('bg-light');
        if (btnBuscarCliente) {
            btnBuscarCliente.innerHTML = '<i class="bi bi-x-circle"></i>';
            btnBuscarCliente.title = 'Limpar seleção';
        }
    }

    const debouncedSearchCliente = debounce(function (termo) {
        buscarClientes(termo);
    }, 300);

    campoBuscaCliente.addEventListener('input', function (e) {
        const termo = e.target.value.trim();

        if (this.readOnly) return;

        if (termo.length >= 2) {
            debouncedSearchCliente(termo);
        } else {
            limparResultadosCliente();
        }
    });

    if (btnBuscarCliente) {
        btnBuscarCliente.addEventListener('click', function (e) {
            e.preventDefault();

            if (document.getElementById('cliente_id') && document.getElementById('cliente_id').value) {
                limparSelecaoCliente();
            } else {
                const termo = campoBuscaCliente.value.trim();
                if (termo.length >= 2) {
                    buscarClientes(termo);
                }
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (!searchResultsCliente) return;

        if (!campoBuscaCliente.contains(e.target) &&
            !searchResultsCliente.contains(e.target) &&
            !btnBuscarCliente?.contains(e.target)) {
            limparResultadosCliente();
        }
    });

    // Atalho Ctrl+K atualizado para focar no novo campo
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            if (!campoBuscaCliente.readOnly) {
                campoBuscaCliente.focus();
            }
        }
    });
}
