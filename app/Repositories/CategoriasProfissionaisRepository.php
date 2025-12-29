<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoriasProfissionaisModel;

class CategoriasProfissionaisRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new CategoriasProfissionaisModel();
    }
}
