<?php

namespace App\Repositories\Contracts;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Toggle the active status of a user.
     *
     * @param int $id
     * @return bool
     */
    public function toggleStatus(int $id): bool;
}
