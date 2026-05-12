<?php

namespace App\Repositories;

use App\Models\Movimentacao;
use App\Repositories\Contracts\MovimentacaoRepositoryInterface;

/**
 * Class MovimentacaoRepository
 * @package App\Repositories
 */
class MovimentacaoRepository extends BaseRepository implements MovimentacaoRepositoryInterface
{
    /**
     * MovimentacaoRepository constructor.
     * @param Movimentacao $model
     */
    public function __construct(Movimentacao $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    protected function applyFilters($query, array $filters)
    {
        $query->with(['user']);

        if (isset($filters['processo_id'])) {
            $query->where('processo_id', $filters['processo_id']);
        }

        return $query->orderBy('created_at', 'desc');
    }
}
