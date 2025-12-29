<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProfissoesModel;

/**
 * Repositorio para gerenciar profissões
 * 
 * @package App\Repositories
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class ProfissoesRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ProfissoesModel();
    }
}
