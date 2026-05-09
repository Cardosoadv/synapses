<?php

namespace App\Repositories;

use App\Models\Processo;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProcessoRepository implements ProcessoRepositoryInterface
{
    protected $model;

    public function __construct(Processo $model)
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

    public function findById(int $id): ?Processo
    {
        return $this->model->with(['tipoProcesso', 'interessado'])->find($id);
    }

    public function create(array $data): Processo
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Processo
    {
        $processo = $this->model->findOrFail($id);
        $processo->update($data);
        return $processo;
    }

    public function delete(int $id): bool
    {
        $processo = $this->model->findOrFail($id);
        return $processo->delete();
    }

    public function getLatestProcessNumber(int $year): ?string
    {
        $latest = $this->model->whereYear('data_abertura', $year)
            ->orderBy('numero', 'desc')
            ->first();
            
        return $latest ? $latest->numero : null;
    }

    protected function applyFilters($query, array $filters)
    {
        $query->with(['tipoProcesso', 'interessado']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('assunto', 'like', "%{$search}%");
            });
        }

        if (isset($filters['tipo_processo_id']) && !empty($filters['tipo_processo_id'])) {
            $query->where('tipo_processo_id', $filters['tipo_processo_id']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc');
    }
}
