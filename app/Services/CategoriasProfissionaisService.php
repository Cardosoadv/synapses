<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoriasProfissionaisRepository;

/**
 * Serviço para gerenciar categorias de profissões
 * 
 * @package App\Services
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class CategoriasProfissionaisService extends BaseService
{
    public function __construct()
    {
        $this->repository = new CategoriasProfissionaisRepository();
    }
}
