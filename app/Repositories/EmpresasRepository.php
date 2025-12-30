<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmpresasModel;

class EmpresasRepository
{
    private EmpresasModel $model;

    public function __construct()
    {
        $this->model = new EmpresasModel();
    }

    public function getAll(int $limit = 0, int $offset = 0): array
    {
        return $this->model->findAll($limit, $offset);
    }

    public function paginate(int $perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function findByCnpj(string $cnpj): ?array
    {
        return $this->model->where('cnpj', $cnpj)->first();
    }

    public function create(array $data): int|false
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function getPager()
    {
        return $this->model->pager;
    }

    public function getModel(): EmpresasModel
    {
        return $this->model;
    }
}
