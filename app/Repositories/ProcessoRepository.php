<?php

namespace App\Repositories;

use App\Models\Processo;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcessoRepository
 * @package App\Repositories
 */
class ProcessoRepository extends BaseRepository implements ProcessoRepositoryInterface
{
    /**
     * ProcessoRepository constructor.
     * @param Processo $model
     */
    public function __construct(Processo $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?Model
    {
        return $this->model->with(['tipoProcesso', 'interessado'])->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getLatestProcessNumber(int $year): ?string
    {
        $startDate = "{$year}-01-01 00:00:00";
        $endDate = "{$year}-12-31 23:59:59";

        $latest = $this->model->where('data_abertura', '>=', $startDate)
            ->where('data_abertura', '<=', $endDate)
            ->orderBy('numero', 'desc')
            ->first();
            
        return $latest ? $latest->numero : null;
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters($query, array $filters): \Illuminate\Database\Eloquent\Builder
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
