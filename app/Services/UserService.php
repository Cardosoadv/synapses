<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all users with optional filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listAll(array $filters = [])
    {
        return $this->repository->paginate($this->repository::DEFAULT_PER_PAGE, $filters);
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return \App\Models\User
     * @throws ModelNotFoundException
     */
    public function findById(int $id)
    {
        $user = $this->repository->findById($id);
        if (!$user) {
            throw (new ModelNotFoundException())->setModel(\App\Models\User::class, [$id]);
        }
        return $user;
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->repository->create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update(int $id, array $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Toggle the active status of a user.
     *
     * @param int $id
     * @return bool
     */
    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }
}
