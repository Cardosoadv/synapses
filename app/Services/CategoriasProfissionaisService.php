<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoriasProfissionaisRepository;

class CategoriasProfissionaisService extends BaseService
{
    public function __construct()
    {
        $this->repository = new CategoriasProfissionaisRepository();
    }
}
