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

    const urls = JSON.parse(dataContainer.getAttribute('data-urls') || '[]');
    // Utiliza a variável global AppConfig
    const baseUrl = AppConfig.baseUrl; 

    const loader = document.getElementById('loader');
    const messagesDiv = document.getElementById('messages');

    if (!baseUrl) {
        // Agora verificamos AppConfig.baseUrl implicitamente
        showMessage('URL base (AppConfig.baseUrl) não configurada.', 'error');
        return;
    }

    /**
     * Exibe uma mensagem na tela.
     * @param {string} message - A mensagem a ser exibida.
     * @param {string} type - 'info', 'success' ou 'error'.
     */
    function showMessage(message, type = 'info') {
        const messageEl = document.createElement('div');
        messageEl.className = `message ${type}`;
        messageEl.textContent = message;
        messagesDiv.appendChild(messageEl);
        // Rola para a última mensagem
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    /**
     * Formata uma URL longa para exibir apenas a OAB/UF.
     * @param {string} url - A URL completa da API.
     * @returns {string} - A string formatada (ex: "164136/MG") ou a URL original se falhar.
     */
    function formatarUrlParaExibicao(url) {
        try {
            const parsedUrl = new URL(url);
            const numeroOab = parsedUrl.searchParams.get('numeroOab');
            const ufOab = parsedUrl.searchParams.get('ufOab');

            if (numeroOab && ufOab) {
                return `${numeroOab}/${ufOab}`;
            }
            return url; // Retorna a URL original se não encontrar os parâmetros
        } catch (e) {
            console.warn('Não foi possível formatar a URL para exibição:', e);
            return url; // Retorna a URL original em caso de erro
        }
    }

    /**
     * Envia os dados JSON para o endpoint de processamento no backend.
     * @param {object} jsonData - Os dados (geralmente 'items') recebidos da API.
     * @param {string} url - A URL original, para fins de log.
     * @returns {Promise<object>} - A promessa com a resposta do servidor de processamento.
     */
    function sendDataToProcessarIntimacoes(jsonData, url) {
        // Retorna a promessa do fetch
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
            
            const urlFormatada = formatarUrlParaExibicao(url); // Formata aqui
            
            if (responseData.success) {
                showMessage(`✓ Sucesso para ${urlFormatada}: ${responseData.message} (Processadas: ${responseData.processadas} | Novas: ${responseData.novas} | Repetidas: ${responseData.repetidas})`, 'success');
                
                if (responseData.erros && responseData.erros.length > 0) {
                    showMessage(`↳ Erros no processamento: ${responseData.erros.length}`, 'error');
                }
            } else {
                // Se success for false, lança um erro
                throw new Error(responseData.message || 'Erro desconhecido no processamento');
            }
            return responseData; // Retorna os dados para o chamador (await)
        });
    }

    /**
     * Busca intimações de uma URL específica.
     * @param {string} url - A URL da API para buscar intimações.
     * @returns {Promise<object>} - A promessa com a resposta do servidor de processamento.
     */
    function fetchIntimacoes(url) {
        const urlFormatada = formatarUrlParaExibicao(url); // Formata aqui
        
        showMessage(`Buscando intimações de: ${urlFormatada}...`, 'info');
        
        // 1. Busca os dados da API externa
        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na busca (API externa): ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados recebidos da API:', data);
                
                // Verifica se 'items' existe e tem conteúdo
                if (!data.items || data.items.length === 0) {
                    showMessage(`↳ Nenhum item encontrado para ${urlFormatada}.`, 'info'); // Usa a formatada
                    return { success: true, message: 'Nenhum item', processadas: 0, erros: [] }; // Retorna sucesso "vazio"
                }

                showMessage(`↳ ${data.items.length} intimações recebidas de ${urlFormatada}. Processando...`, 'info'); // Usa a formatada
                
                // 2. Envia os dados para o backend local processar
                return sendDataToProcessarIntimacoes(data, url); // Passa a URL original para o processamento
            });
    }

    /**
     * Função principal assíncrona para processar todas as URLs em sequência.
     */
    async function processarTodasUrls() {
        loader.style.display = 'block';
        messagesDiv.innerHTML = ''; // Limpa mensagens anteriores
        
        if (urls.length === 0) {
            showMessage('Nenhuma URL de advogado para processar.', 'info');
            loader.style.display = 'none';
            return;
        }

        showMessage(`Iniciando processamento de ${urls.length} URL(s)...`, 'info');
        let sucessoGeral = 0;
        let falhaGeral = 0;

        // Itera sobre as URLs uma por uma (sequencialmente)
        for (const url of urls) {
            try {
                // await espera a conclusão do fetch E do processamento
                await fetchIntimacoes(url);
                sucessoGeral++;
            } catch (error) {
                console.error(`Erro ao processar ${url}:`, error);
                const urlFormatada = formatarUrlParaExibicao(url); // Formata aqui
                // Exibe a falha e continua para a próxima URL
                showMessage(`✗ Falha total ao processar ${urlFormatada}: ${error.message}`, 'error');
            }
        }

        // Quando tudo terminar
        loader.style.display = 'none';
        showMessage('-----------------------------------', 'info');
        showMessage('Processamento concluído.', 'info');
        showMessage(`Total de URLs com sucesso: ${sucessoGeral}`, 'success');
        showMessage(`Total de URLs com falha: ${falhaGeral}`, 'error');

        // Opcional: Redireciona após 5 segundos
        // setTimeout(() => {
        //    window.location.href = `${AppConfig.baseUrl}intimacoes`;
        // }, 5000);
    }

    // Inicia o processo
    processarTodasUrls();
});