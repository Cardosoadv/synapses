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

    public function delete(int $id)
    {
        // Soft delete employee
        return $this->model->delete($id);
        // Should we delete User? Usually keeping user but deactivating might be safer, 
        // but if Employee is deleted, User likely should be too or at least deactivated.
        // Logic handled in Service.
    }
}
