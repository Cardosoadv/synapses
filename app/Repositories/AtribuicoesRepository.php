<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AtribuicoesModel;

class AtribuicoesRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new AtribuicoesModel();
    }
}
