<?php

namespace App\Repositories;

use App\Models\TipoProcesso;
use App\Repositories\Contracts\TipoProcessoRepositoryInterface;

/**
 * Class TipoProcessoRepository
 * @package App\Repositories
 */
class TipoProcessoRepository extends BaseRepository implements TipoProcessoRepositoryInterface
{
    /**
     * TipoProcessoRepository constructor.
     * @param TipoProcesso $model
     */
    public function __construct(TipoProcesso $model)
    {
        parent::__construct($model);
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
