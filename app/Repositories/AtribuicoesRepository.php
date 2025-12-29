<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AtribuicoesModel;


/**
 * Repositorio para gerenciar atribuicoes
 * 
 * @package App\Repositories
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class AtribuicoesRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new AtribuicoesModel();
    }
}
