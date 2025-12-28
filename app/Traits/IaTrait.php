<?php

namespace App\Traits;

trait IaTrait
{

    // Configuração da API do Gemini
    protected $apiGeminiModel = 'gemini-2.0-flash';
    protected $apiGeminiUrlBase = 'https://generativelanguage.googleapis.com/v1beta/models/';
    protected string $apiKeyGemini;
    protected string $geminiApiUrl;

    public function __construct()
    {
        // Construtor vazio para evitar conflitos
    }

    private function ensureGeminiInitialized()
    {
        if (isset($this->geminiApiUrl)) {
            return;
        }

        $this->apiKeyGemini = getenv('API_KEY_GEMINI');

        if (!$this->apiKeyGemini) {
            log_message('error', 'API_KEY_GEMINI não configurada no ambiente.');
            return;
        }

        $this->geminiApiUrl = $this->apiGeminiUrlBase . $this->apiGeminiModel . ':generateContent?key=' . $this->apiKeyGemini;
    }


    /**
     * Método auxiliar para chamada à API externa (Gemini).
     *
     * @param array $payload
     * @return object|null
     */
    public function callGeminiApi(array $payload): ?object
    {
        $this->ensureGeminiInitialized();

        if (empty($this->geminiApiUrl)) {
            log_message('error', 'URL da API Gemini não inicializada.');
            return null;
        }

        $ch = curl_init($this->geminiApiUrl);

        if ($ch === false) {
            log_message('error', 'Falha ao inicializar cURL');
            return null;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                "x-goog-api-key: $this->apiKeyGemini"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if ($curlError) {
            log_message('error', "Erro cURL: {$curlError}");
            return null;
        }

        if ($httpCode !== 200) {
            log_message('error', "API Gemini retornou HTTP {$httpCode}. Response: " . substr($response, 0, 500));
            return null;
        }

        if ($response) {
            $decoded = json_decode($response);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            } else {
                log_message('error', 'Erro ao decodificar JSON: ' . json_last_error_msg());
            }
        }

        return null;
    }

    public function gerarResumoComGemini(string $textoIntegra): string
    {
        // Valida se há texto
        if (empty(trim($textoIntegra))) {
            return "Texto vazio. Não é possível gerar resumo.";
        }

        // Limita o tamanho do texto para a API (max 30000 caracteres)
        $textoLimitado = mb_substr($textoIntegra, 0, 30000);

        $systemPrompt = "Você é um assistente jurídico especializado em condensar textos de decisões judiciais brasileiras. Sua tarefa é extrair um resumo profissional, conciso e objetivo em português, com no máximo 5 frases, destacando:\n1. O cerne da controvérsia jurídica\n2. A decisão tomada\n3. O fundamento legal principal\n\nMantenha o tom formal e técnico-jurídico. NÃO inclua introduções, saudações ou conclusões genéricas.";

        $userQuery = "Gere um resumo executivo da seguinte decisão judicial:\n\n" . $textoLimitado;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $userQuery]
                    ]
                ]
            ],
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 500,
            ]
        ];


        $maxRetries = 3;
        $delay = 2;

        for ($i = 0; $i < $maxRetries; $i++) {
            $response = $this->callGeminiApi($payload);

            if ($response && isset($response->candidates[0]->content->parts[0]->text)) {
                $resumo = trim($response->candidates[0]->content->parts[0]->text);
                log_message('info', 'Resumo gerado com sucesso pela IA');
                return $resumo;
            }

            // Log do erro
            if (isset($response->error)) {
                log_message('error', 'Erro da API Gemini: ' . json_encode($response->error));
            }

            // Implementação de Backoff Exponencial
            if ($i < $maxRetries - 1) {
                $p = $i + 1;
                log_message('warning', "Tentativa {$p} falhou. Aguardando {$delay}s antes de tentar novamente...");
                sleep($delay);
                $delay *= 2;
            }
        }

        log_message('error', 'Falha ao gerar resumo da IA após ' . $maxRetries . ' tentativas.');
        return "Não foi possível gerar um resumo automático no momento. Tente novamente mais tarde ou verifique se a chave da API está configurada corretamente.";
    }
}
