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

    const url = dataContainer.getAttribute('data-url-intimacao');
    const deveAtualizarIntimacao = dataContainer.getAttribute('data-atualizar-intimacao');
    
    // Verifica se a URL existe e não está vazia
    if (!url || url.trim() === '') {
        console.warn('URL de intimação não encontrada ou vazia.');
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
     * Envia os dados JSON para o endpoint de processamento no backend.
     * @param {object} jsonData - Os dados (geralmente 'items') recebidos da API.
     * @param {string} url - A URL original, para fins de log.
     * @returns {Promise<object>} - A promessa com a resposta do servidor de processamento.
     */
    function sendDataToProcessarIntimacoes(jsonData, url) {
        return fetch(`${AppConfig.baseUrl}intimacoes/processarIntimacoes`, {
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
                mostrarMensagem(`✓ Sucesso para ${urlFormatada}: ${responseData.message} (Processadas: ${responseData.processadas} | Novas: ${responseData.novas} | Repetidas: ${responseData.repetidas})`, 'success');
                
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
    function fetchIntimacoes(url) {
        const urlFormatada = formatarUrlParaExibicao(url);
        
        mostrarMensagem(`Buscando intimações de: ${urlFormatada}...`, 'info');
        
        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na busca (API externa): ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados recebidos da API:', data);
                
                if (!data.items || data.items.length === 0) {
                    mostrarMensagem(`↳ Nenhum item encontrado para ${urlFormatada}.`, 'info');
                    return { success: true, message: 'Nenhum item', processadas: 0, erros: [] };
                }

                mostrarMensagem(`↳ ${data.items.length} intimações recebidas de ${urlFormatada}. Processando...`, 'info');
                
                return sendDataToProcessarIntimacoes(data, url);
            });
    }

    /**
     * Função principal assíncrona para processar todas as URLs em sequência.
     */
    async function processarIntimacoes() {
        
        if (deveAtualizarIntimacao === false || deveAtualizarIntimacao === 'false') {
            mostrarMensagem('Intimações já atualizadas.', 'info');
            return;
        }

        mostrarMensagem(`Iniciando processamento das Intimações...`, 'info');
        let sucessoGeral = 0;
        let falhaGeral = 0;
        
        try {
            await fetchIntimacoes(url);
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
    processarIntimacoes();
});