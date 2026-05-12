<?php

namespace App\Repositories\Contracts;

use App\Models\Movimentacao;
use Illuminate\Database\Eloquent\Collection;

interface MovimentacaoRepositoryInterface
{
    public function create(array $data): Movimentacao;
    public function findByProcesso(int $processoId): Collection;
}
