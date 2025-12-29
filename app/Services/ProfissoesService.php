<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfissoesRepository;

class ProfissoesService extends BaseService
{
    public function __construct()
    {
        $this->repository = new ProfissoesRepository();
    }
}
