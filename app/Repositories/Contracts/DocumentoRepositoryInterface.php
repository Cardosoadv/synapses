<?php

namespace App\Repositories\Contracts;

use App\Models\Documento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DocumentoRepositoryInterface
{
    public function findAll(array $filters = []): Collection;
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Documento;
    public function findByUuid(string $uuid): ?Documento;
    public function create(array $data): Documento;
    public function update(int $id, array $data): Documento;
    public function delete(int $id): bool;
    public function getByProcesso(int $processoId): Collection;
}
