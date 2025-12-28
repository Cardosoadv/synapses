<?php

namespace App\Traits;

/**
 * Trait FormataDataTrait
 *
 * Fornece métodos para formatar datas entre o formato brasileiro (d/m/Y) e o formato de banco de dados (Y-m-d).
 * @method dataParaBancoDados Formata a data para o formato de banco de dados (Y-m-d).
 * @method dataDoBancoDados Formata a data do banco de dados (Y-m-d) para o formato brasileiro (d/m/Y).
 */
trait FormataDataTrait
{

    /**
     * Formata a data para o formato brasileiro (d/m/Y).
     *
     * @param string|null $data A data a ser formatada.
     * @return string|null A data formatada ou null se a entrada for null.
     */
    public function dataParaBancoDados($data)
    {
        if (!$data) {
            return null;
        }

        $dateTime = \DateTime::createFromFormat('d/m/Y', $data);
        if (!$dateTime) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $data);
            if (!$dateTime) {
                throw new \InvalidArgumentException('Invalid date format');
            }
        }

        return $dateTime->format('Y-m-d');
    }

    /**
     * Formata a data para o formato brasileiro (d/m/Y).
     *
     * @param string|null $data A data a ser formatada.
     * @return string|null A data formatada ou null se a entrada for null.
     */
    public function dataDoBancoDados($data)
    {
        if (!$data) {
            return null;
        }
        $dateTime = \DateTime::createFromFormat('Y-m-d', $data);
        if (!$dateTime) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        return $dateTime->format('d/m/Y');
    }
}
