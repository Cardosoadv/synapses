<?php

namespace App\Repositories;

use App\Models\TipoProcesso;
use App\Repositories\Contracts\TipoProcessoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TipoProcessoRepository implements TipoProcessoRepositoryInterface
{
    protected $model;

    public function __construct(TipoProcesso $model)
    {
        $this->model = $model;
    }

    public function findAll(array $filters = []): Collection
    {
        return $this->applyFilters($this->model->query(), $filters)->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->applyFilters($this->model->query(), $filters)->paginate($perPage);
    }

    public function findById(int $id): ?TipoProcesso
    {
        return $this->model->find($id);
    }

    public function create(array $data): TipoProcesso
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): TipoProcesso
    {
        $tipo = $this->model->findOrFail($id);
        $tipo->update($data);
        return $tipo;
    }

    public function delete(int $id): bool
    {
        $tipo = $this->model->findOrFail($id);
        return $tipo->delete();
    }

    protected function applyFilters($query, array $filters)
    {
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('nome', 'like', "%{$search}%");
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('nome', 'asc');
    }
}
