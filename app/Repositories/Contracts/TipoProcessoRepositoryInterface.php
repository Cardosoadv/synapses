<?php

namespace App\Repositories\Contracts;

use App\Models\TipoProcesso;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TipoProcessoRepositoryInterface
{
    public function findAll(array $filters = []): Collection;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?TipoProcesso;
    public function create(array $data): TipoProcesso;
    public function update(int $id, array $data): TipoProcesso;
    public function delete(int $id): bool;
}
