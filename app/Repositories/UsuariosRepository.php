<?php

namespace App\Repositories;

use App\Models\UsuariosModel;
use CodeIgniter\Shield\Entities\User;

class UsuariosRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UsuariosModel();
    }

    public function getPaginatedUsers(int $perPage = 10, string $search = null)
    {
        // Use alias 'identities' to avoid potential collisions with Shield's internal joins
        $this->model->select('users.*, identities.secret as email');
        $this->model->join('auth_identities as identities', 'identities.user_id = users.id AND identities.type = "email_password"', 'left');
        
        if ($search) {
            $this->model->groupStart()
                ->like('users.username', $search)
                ->orLike('identities.secret', $search)
            ->groupEnd();
        }
        
        $users = $this->model->asArray()->paginate($perPage);
        $pager = $this->model->pager;

        // Enrich with groups and permissions count
        foreach ($users as &$user) {
            // Re-fetch entity to get groups/permissions easily using Shield's methods.
            // Using a fresh instance or clearing state might be safer but findById should work.
            $userEntity = $this->model->findById($user['id']); 
            
            $user['groups'] = $userEntity ? $userEntity->getGroups() : [];
            $user['permissions_count'] = $userEntity ? count($userEntity->getPermissions()) : 0;
        }

        return [
            'users' => $users,
            'pager' => $pager,
            'total' => $this->model->countAllResults(false) // Approximate
        ];
    }

    public function findById(int $id)
    {
        // Return array with everything needed for the form
        $userEntity = $this->model->findById($id);
        
        if (!$userEntity) {
            return null;
        }

        $userData = $userEntity->toArray();
        $userData['email'] = $userEntity->getEmail();
        $userData['groups'] = $userEntity->getGroups();
        $userData['permissions'] = $userEntity->getPermissions();
        
        return $userData;
    }

    public function delete(int $id)
    {
        return $this->model->delete($id);
    }
}
