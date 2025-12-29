<?php

namespace App\Repositories;

use App\Models\FuncionariosModel;
use CodeIgniter\Shield\Models\UserModel;

class FuncionariosRepository
{
    protected $model;
    protected $userModel;

    public function __construct()
    {
        $this->model = new FuncionariosModel();
        $this->userModel = new UserModel();
    }

    public function getPaginated(int $perPage = 10, string $search = null)
    {
        $this->model->select('employees.*, users.username, users.active, users.last_active, identities.secret as email');
        $this->model->join('users', 'users.id = employees.user_id', 'left');
        $this->model->join('auth_identities as identities', 'identities.user_id = users.id AND identities.type = "email_password"', 'left');

        if ($search) {
            $this->model->groupStart()
                ->like('employees.registration_number', $search)
                ->orLike('users.username', $search)
                ->orLike('identities.secret', $search)
            ->groupEnd();
        }

        return [
            'funcionarios' => $this->model->paginate($perPage),
            'pager'        => $this->model->pager,
            'total'        => $this->model->countAllResults(false)
        ];
    }

    public function findById(int $id)
    {
        // Join everything to get a complete picture
        $this->model->select('employees.*, users.username, users.active, users.id as user_auth_id');
        $this->model->join('users', 'users.id = employees.user_id', 'left');
        
        $funcionario = $this->model->find($id);
        
        if (!$funcionario) {
            return null;
        }

        // Fetch User Entity for groups/email/permissions
        $userEntity = $this->userModel->findById($funcionario['user_auth_id']);
        
        if ($userEntity) {
            $funcionario['email']       = $userEntity->getEmail();
            $funcionario['groups']      = $userEntity->getGroups();
            $funcionario['permissions'] = $userEntity->getPermissions();
        }

        return $funcionario;
    }

    public function getFuncionarioByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function create(array $userInfo, array $employeeInfo): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Create Shield User
            $user = new \CodeIgniter\Shield\Entities\User([
                'username' => $userInfo['username'],
                'email'    => $userInfo['email'],
                'password' => $userInfo['password'],
                'active'   => $userInfo['active'] ?? 0,
            ]);
            $this->userModel->save($user);
            
            // Get inserted User ID
            $user = $this->userModel->findById($this->userModel->getInsertID());
            
            // Sync Groups/Permissions
            if (!empty($userInfo['groups'])) {
                $user->syncGroups(...$userInfo['groups']);
            }
            if (!empty($userInfo['permissions'])) {
                foreach ($userInfo['permissions'] as $perm) {
                    $user->addPermission($perm);
                }
            }

            // 2. Create Employee Record linked to User
            $employeeInfo['user_id'] = $user->id;
            $this->model->insert($employeeInfo);

            $db->transComplete();

            return $db->transStatus();

        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function createEmployee(array $employeeInfo): bool
    {
        return $this->model->insert($employeeInfo) !== false;
    }

    public function update(int $id, array $userInfo, array $employeeInfo): bool
    {
        $funcionario = $this->model->find($id);
        if (!$funcionario) {
            return false;
        }

        $user = $this->userModel->findById($funcionario['user_id']);
        if (!$user) {
             return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Update Shield User
            $user->username = $userInfo['username'];
            if (!empty($userInfo['password'])) {
                $user->password = $userInfo['password'];
            }
            $user->active = $userInfo['active'] ?? 0;
            $this->userModel->save($user);

            // Update Email Identity if changed
            if ($user->email !== $userInfo['email']) {
                 $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                 $identity = $identityModel->getIdentityByType($user, 'email_password');
                 if ($identity) {
                     $identity->secret = $userInfo['email'];
                     $identityModel->save($identity);
                 }
            }

            // Groups & Permissions
            if (isset($userInfo['groups'])) {
                $user->syncGroups(...$userInfo['groups']);
            } else {
                $user->syncGroups();
            }
            if (isset($userInfo['permissions'])) {
                 $user->syncPermissions(...$userInfo['permissions']);
            } else {
                 $user->syncPermissions();
            }

            // 2. Update Employee Record
            $this->model->update($id, $employeeInfo);

            $db->transComplete();
            return $db->transStatus();

        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        $funcionario = $this->model->find($id);
        if (!$funcionario) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Soft delete employee
            $this->model->delete($id);
            
            // Delete User (Shield)
            $this->userModel->delete($funcionario['user_id']);
    
            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }
}
