<?php

namespace App\Repositories\Contracts;

use App\Models\Processo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProcessoRepositoryInterface
{
    public function findAll(array $filters = []): Collection;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Processo;
    public function create(array $data): Processo;
    public function update(int $id, array $data): Processo;
    public function delete(int $id): bool;
    public function getLatestProcessNumber(int $year): ?string;
}
