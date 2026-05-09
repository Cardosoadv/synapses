<?php

namespace App\Services;

use App\Repositories\Contracts\TipoProcessoRepositoryInterface;
use Illuminate\Support\Collection;

class TipoProcessoService
{
    protected $repository;

    public function __construct(TipoProcessoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listAll(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function getAllActive()
    {
        return $this->repository->findAll(['is_active' => true]);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
