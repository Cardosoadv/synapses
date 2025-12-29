<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfissoesRepository;

/**
 * Serviço para gerenciar profissões
 * 
 * @package App\Services
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class ProfissoesService extends BaseService
{
    public function __construct()
    {
        $this->repository = new ProfissoesRepository();
    }
}
