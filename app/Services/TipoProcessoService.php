<?php

namespace App\Services;

use App\Models\TipoProcesso;
use App\Repositories\Contracts\TipoProcessoRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class TipoProcessoService
 * @package App\Services
 */
class TipoProcessoService
{
    /**
     * @var TipoProcessoRepositoryInterface
     */
    protected $repository;

    /**
     * TipoProcessoService constructor.
     * @param TipoProcessoRepositoryInterface $repository
     */
    public function __construct(TipoProcessoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all process types with optional filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listAll(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->paginate(15, $filters);
    }

    /**
     * Get all active process types.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        return $this->repository->findAll(['is_active' => true]);
    }

    /**
     * Find a process type by ID.
     *
     * @param int $id
     * @return \App\Models\TipoProcesso
     * @throws ModelNotFoundException
     */
    public function findById(int $id): TipoProcesso
    {
        $tipoProcesso = $this->repository->findById($id);

        if (!$tipoProcesso) {
            throw (new ModelNotFoundException())->setModel(TipoProcesso::class, [$id]);
        }

        return $tipoProcesso;
    }

    /**
     * Create a new process type.
     *
     * @param array $data
     * @return TipoProcesso
     */
    public function create(array $data): TipoProcesso
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing process type.
     *
     * @param int $id
     * @param array $data
     * @return TipoProcesso
     */
    public function update(int $id, array $data): TipoProcesso
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a process type by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
