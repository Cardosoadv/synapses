<?php namespace App\Traits;

trait FormataNumeroProcessoTrait
{
    /**
     * Formata uma string de número de processo bruta para o formato mascarado.
     *
     * @param string $numeroProcessoBruto A string do número de processo bruto (somente dígitos).
     * @return string|null O número de processo formatado ou null se for inválido.
     */
    protected function formatarNumeroProcesso(string $numeroProcessoBruto): ?string
    {
        // Remove todos os caracteres não-dígitos e trunca para 20 caracteres
        $numeroLimpo = preg_replace('/\D/', '', $numeroProcessoBruto);
        $numeroLimpo = substr($numeroLimpo, 0, 20);

        // Regex para o formato esperado: 7 dígitos, 2 dígitos, 4 dígitos, 1 dígito, 2 dígitos, 4 dígitos
        $regex = '/^(\d{7})(\d{2})(\d{4})(\d{1})(\d{2})(\d{4})$/';

        if (preg_match($regex, $numeroLimpo, $partesMascara)) {
            // $partesMascara[0] é a string completa correspondente, os elementos seguintes são os grupos capturados
            $primeiraParte = $partesMascara[1];
            $segundaParte = $partesMascara[2];
            $terceiraParte = $partesMascara[3];
            $quartaParte = $partesMascara[4];
            $quintaParte = $partesMascara[5];
            $sextaParte = $partesMascara[6];

            return "{$primeiraParte}-{$segundaParte}.{$terceiraParte}.{$quartaParte}.{$quintaParte}.{$sextaParte}";
        }

        return null; // Retorna null para números de processo inválidos
    }

    /**
     * Valida se uma dada string é um número de processo formatado corretamente.
     *
     * @param string $numeroProcessoFormatado A string do número de processo formatado.
     * @return bool Verdadeiro se for válido, falso caso contrário.
     */
    protected function ehFormatoNumeroProcessoValido(string $numeroProcessoFormatado): bool
    {
        // Este regex verifica especificamente o formato mascarado: XXXXXXX-XX.XXXX.X.XX.XXXX
        $regex = '/^\d{7}-\d{2}\.\d{4}\.\d{1}\.\d{2}\.\d{4}$/';
        return (bool) preg_match($regex, $numeroProcessoFormatado);
    }
}