<?php

namespace App\Traits;



trait VerificaPermissoesTrait
{

    /**
     * Verifica se o usuário logado tem uma permissão específica.
     *
     * @param string $permission A permissão a ser verificada (padrão: 'module.clientes').
     * @return bool Verdadeiro se o usuário tiver a permissão, falso caso contrário.
     */
    protected function verificaPermissao(string $permission, string $action = 'access'): bool
    {
        return auth()->user()->can($permission . '.' . $action);
    }

}