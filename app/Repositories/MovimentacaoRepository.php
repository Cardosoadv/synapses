<?php

namespace App\Repositories;

use App\Models\Movimentacao;
use App\Repositories\Contracts\MovimentacaoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MovimentacaoRepository implements MovimentacaoRepositoryInterface
{
    protected $model;

    public function __construct(Movimentacao $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Movimentacao
    {
        return $this->model->create($data);
    }

    public function findByProcesso(int $processoId): Collection
    {
        return $this->model->where('processo_id', $processoId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
