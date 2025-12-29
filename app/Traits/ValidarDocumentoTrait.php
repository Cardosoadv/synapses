<?php

namespace App\Traits;

/**
 * Trait ValidarDocumentoTrait
 *
 * Fornece funcionalidades para validação de CPF e CNPJ.
 */
trait ValidarDocumentoTrait
{
    /**
     * Valida um número de CPF.
     *
     * Verifica se o CPF possui 11 dígitos, se não é uma sequência repetida
     * e valida os dígitos verificadores.
     *
     * @param string|null $cpf O CPF a ser validado.
     * @return bool True se o CPF for válido, false caso contrário.
     */
    public function validaCpf(?string $cpf): bool
    {
        if (empty($cpf)) {
            return false;
        }

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida um número de CNPJ.
     *
     * Verifica se o CNPJ possui 14 dígitos, se não é uma sequência repetida
     * e valida os dígitos verificadores.
     *
     * @param string|null $cnpj O CNPJ a ser validado.
     * @return bool True se o CNPJ for válido, false caso contrário.
     */
    public function validaCnpj(?string $cnpj): bool
    {
        if (empty($cnpj)) {
            return false;
        }

        // Extrai somente os números
        $cnpj = preg_replace('/[^0-9]/is', '', $cnpj);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

}
