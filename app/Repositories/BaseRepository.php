<?php

declare(strict_types=1);

namespace App\Repositories;

use CodeIgniter\Model;

class BaseRepository
{
    protected Model $model;

    public function findAll(int $limit = 0, int $offset = 0): array
    {
        return $this->model->findAll($limit, $offset);
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function create(array $data): int
    {
        return (int) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}
