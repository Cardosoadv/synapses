<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface BaseRepositoryInterface
{
    /**
     * Default number of items per page.
     */
    const DEFAULT_PER_PAGE = 15;

    /**
     * Find all records with optional filters.
     *
     * @param array $filters
     * @return Collection
     */
    public function findAll(array $filters = []): Collection;

    /**
     * Paginate records with optional filters.
     *
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = self::DEFAULT_PER_PAGE, array $filters = []): LengthAwarePaginator;

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model;

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
