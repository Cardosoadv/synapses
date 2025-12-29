<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoriasProfissionaisModel;

/**
 * Repositorio para gerenciar categorias de profissões
 * 
 * @package App\Repositories
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class CategoriasProfissionaisRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new CategoriasProfissionaisModel();
    }
}
