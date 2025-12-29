<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AtribuicoesRepository;

class AtribuicoesService extends BaseService
{
    public function __construct()
    {
        $this->repository = new AtribuicoesRepository();
    }
}
