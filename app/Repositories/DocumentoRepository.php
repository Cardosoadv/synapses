<?php

namespace App\Repositories;

use App\Models\Documento;
use App\Repositories\Contracts\DocumentoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentoRepository implements DocumentoRepositoryInterface
{
    protected $model;

    public function __construct(Documento $model)
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

    public function findById(int $id): ?Documento
    {
        return $this->model->with(['processo', 'user'])->find($id);
    }

    public function findByUuid(string $uuid): ?Documento
    {
        return $this->model->with(['processo', 'user'])->where('uuid', $uuid)->first();
    }

    public function create(array $data): Documento
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Documento
    {
        $documento = $this->model->findOrFail($id);
        $documento->update($data);
        return $documento;
    }

    public function delete(int $id): bool
    {
        $documento = $this->model->findOrFail($id);
        return $documento->delete();
    }

    public function getByProcesso(int $processoId): Collection
    {
        return $this->model->where('processo_id', $processoId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function applyFilters($query, array $filters)
    {
        $query->with(['processo', 'user']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('numero_documento', 'like', "%{$search}%");
            });
        }

        if (isset($filters['processo_id']) && !empty($filters['processo_id'])) {
            $query->where('processo_id', $filters['processo_id']);
        }

        if (isset($filters['tipo_documento']) && !empty($filters['tipo_documento'])) {
            $query->where('tipo_documento', $filters['tipo_documento']);
        }

        return $query->orderBy('created_at', 'desc');
    }
}
