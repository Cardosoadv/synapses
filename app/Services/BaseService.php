<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BaseRepository;
use Exception;

class BaseService
{
    protected BaseRepository $repository;

    public function listAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): int
    {
        // Simple transaction wrapper for consistency
        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $id = $this->repository->create($data);
            $db->transComplete();
            return $id;
        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();
        try {
            $result = $this->repository->update($id, $data);
            $db->transComplete();
            return $result;
        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
