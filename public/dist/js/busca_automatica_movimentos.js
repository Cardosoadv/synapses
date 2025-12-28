/**
 * Script para receber intimações de múltiplas URLs.
 * Este script depende de um elemento no DOM com id "receiver-data"
 * que contenha o atributo "data-urls" (JSON string) e que AppConfig.baseUrl
 * esteja globalmente disponível.
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Obter os dados do elemento no HTML
    const dataContainer = document.getElementById('receiver-data');
    if (!dataContainer) {
        console.error('Elemento #receiver-data não encontrado.');
        return;
    }

    const url = dataContainer.getAttribute('data-url-movimento');
    const deveAtualizarMovimentos = dataContainer.getAttribute('data-atualizar-intimacao');
    const API_KEY = dataContainer.getAttribute('data-api-key');
    const numeroProcesso = dataContainer.getAttribute('data-numero-processo');
    
    // Verifica se a URL existe e não está vazia
    if (!url || url.trim() === '') {
        console.warn('URL de Movimentos não encontrada ou vazia.');
        return;
    }
    
    // Utiliza a variável global AppConfig
    const baseUrl = AppConfig.baseUrl; 

    if (!baseUrl) {
        mostrarMensagem('URL base (AppConfig.baseUrl) não configurada.', 'error');
        return;
    }

    /**
     * Formata uma URL longa para exibir apenas a OAB/UF.
     * @param {string} url - A URL completa da API.
     * @returns {string}
     */
    function formatarUrlParaExibicao(url) {
        try {
            const parsedUrl = new URL(url);
            const numeroProcesso = parsedUrl.searchParams.get('numeroProcesso');

            if (numeroProcesso) {
                return `${numeroProcesso}`;
            }
            return url;
        } catch (e) {
            console.warn('Não foi possível formatar a URL para exibição:', e);
            return url;
        }
    }

    /**
 * Busca movimentos de um processo específico na API DataJud.
 * @param {string} url - A URL da API.
 * @param {string} apiKey - A chave de API para autenticação.
 * @param {string} numeroProcesso - O número do processo.
 * @returns {Promise<object>} - A promessa com os dados da API.
 */
function fetchMovimentosDatajud(url, apiKey, numeroProcesso) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Authorization': apiKey,
            'Content-Type': 'application/json',
            'x-li-format': 'json',
            'User-Agent': 'insomnia/10.1.1'
        },
        body: JSON.stringify({
            query: {
                match: {
                    numeroProcesso: numeroProcesso
                }
            }
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro na busca (API DataJud): ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Dados recebidos da API DataJud:', data);
        return data;
    });
}

    /**
     * Envia os dados JSON para o endpoint de processamento no backend.
     * @param {object} jsonData - Os dados (geralmente 'items') recebidos da API.
     * @param {string} url - A URL original, para fins de log.
     * @returns {Promise<object>} - A promessa com a resposta do servidor de processamento.
     */
    function sendDataToProcessarMovimentos(jsonData, url) {
        return fetch(`${AppConfig.baseUrl}movimentos/processarMovimentosDatajud`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro no processamento no servidor: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Resposta do servidor:', responseData);
            
            const urlFormatada = formatarUrlParaExibicao(url);
            
            if (responseData.success) {
                mostrarMensagem(`✓ Sucesso para ${urlFormatada}: ${responseData.message} (Processadas: ${responseData.processadas})`, 'success');
                
                if (responseData.erros && responseData.erros.length > 0) {
                    mostrarMensagem(`↳ Erros no processamento: ${responseData.erros.length}`, 'error');
                }
            } else {
                throw new Error(responseData.message || 'Erro desconhecido no processamento');
            }
            return responseData;
        });
    }

    /**
 * Busca intimações de uma URL específica.
 * @param {string} url - A URL da API para buscar intimações.
 * @returns {Promise<object>} - A promessa com a resposta do servidor de processamento.
 */
function fetchMovimentos(url) {
    const urlFormatada = formatarUrlParaExibicao(url);
    
    mostrarMensagem(`Buscando movimentos de: ${urlFormatada}...`, 'info');
    
    return fetchMovimentosDatajud(url, API_KEY, numeroProcesso)
        .then(data => {
            if (!data.items || data.items.length === 0) {
                mostrarMensagem(`↳ Nenhum item encontrado para ${urlFormatada}.`, 'info');
                return { success: true, message: 'Nenhum item', processadas: 0, erros: [] };
            }

            mostrarMensagem(`↳ ${data.items.length} movimentos recebidos de ${urlFormatada}. Processando...`, 'info');
            
            return sendDataToProcessarMovimentos(data, url);
        });
}

    /**
     * Função principal assíncrona para processar todas as URLs em sequência.
     */
    async function processarMovimentos() {
        
        if (deveAtualizarMovimentos === false || deveAtualizarMovimentos === 'false') {
            mostrarMensagem('Movimentos já atualizados.', 'info');
            return;
        }

        mostrarMensagem(`Iniciando processamento dos Movimentos...`, 'info');
        let sucessoGeral = 0;
        let falhaGeral = 0;
        
        try {
            await fetchMovimentos(url);
            sucessoGeral++;
        } catch (error) {
            console.error(`Erro ao processar ${url}:`, error);
            const urlFormatada = formatarUrlParaExibicao(url);
            mostrarMensagem(`✗ Falha total ao processar ${urlFormatada}: ${error.message}`, 'error');
            falhaGeral++;
        }

        mostrarMensagem('Processamento de intimações concluído.', 'info');
    }

    // Inicia o processo
    processarMovimentos();
});