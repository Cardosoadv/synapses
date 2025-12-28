<?php

namespace App\Services;

use App\Models\FuncionariosModel;
use App\Repositories\FuncionariosRepository;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class FuncionariosService
{
    protected $repository;
    protected $model;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->repository = new FuncionariosRepository();
        $this->model      = new FuncionariosModel();
        $this->userModel  = new UserModel();
        $this->db         = \Config\Database::connect();
    }

    public function getAll(int $perPage = 10, string $search = null)
    {
        return $this->repository->getPaginated($perPage, $search);
    }

    public function getById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data, $file = null)
    {
        $this->db->transStart();

        try {
            // 1. Create Shield User
            $user = new User([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'active'   => isset($data['active']) ? 1 : 0,
            ]);
            $this->userModel->save($user);
            
            // Get inserted User ID
            $user = $this->userModel->findById($this->userModel->getInsertID());
            $userId = $user->id;

            // Sync Groups/Permissions
            if (!empty($data['groups'])) {
                $user->syncGroups(...$data['groups']);
            }
            if (!empty($data['permissions'])) {
                foreach ($data['permissions'] as $perm) {
                    $user->addPermission($perm);
                }
            }

            // 2. Handle File Upload
            $photoPath = null;
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/funcionarios', $newName);
                $photoPath = $newName;
            }

            // 3. Create Employee Record
            $employeeData = [
                'user_id'             => $userId,
                'registration_number' => $data['registration_number'],
                'position'            => $data['position'],
                'department'          => $data['department'] ?? null,
                'status'              => isset($data['active']) ? 'active' : 'inactive', // Map active to enum
                'is_lawyer'           => isset($data['is_lawyer']) ? 1 : 0,
                'rateio_ativo'        => isset($data['rateio_ativo']) ? 1 : 0,
                'oab_numero'          => $data['oab_numero'] ?? null,
                'oab_uf'              => $data['oab_uf'] ?? null,
                'photo'               => $photoPath,
            ];

            $this->model->insert($employeeData);

            $this->db->transComplete();

            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function update(int $id, array $data, $file = null)
    {
        $funcionario = $this->model->find($id);
        if (!$funcionario) {
            throw new \Exception("Funcionário não encontrado.");
        }

        $userId = $funcionario['user_id'];
        $user   = $this->userModel->findById($userId);

        $this->db->transStart();

        try {
            // 1. Update Shield User
            $user->username = $data['username'];
            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->active = isset($data['active']) ? 1 : 0;
            $this->userModel->save($user);

            // Update Email Identity if changed
            if ($user->email !== $data['email']) {
                 // Check if email already taken? Shield handles this mostly but we need to update identity.
                 // Manual update of identity
                 $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
                 $identity = $identityModel->getIdentityByType($user, 'email_password');
                 if ($identity) {
                     $identity->secret = $data['email'];
                     $identityModel->save($identity);
                 }
            }

            // Groups & Permissions
            if (isset($data['groups'])) {
                $user->syncGroups(...$data['groups']);
            } else {
                $user->syncGroups();
            }
            if (isset($data['permissions'])) {
                 $user->syncPermissions(...$data['permissions']);
            } else {
                 $user->syncPermissions();
            }

            // 2. Handle File Upload
            $photoPath = $funcionario['photo'];
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/funcionarios', $newName);
                $photoPath = $newName;
                
                // Delete old photo?
                if ($funcionario['photo'] && file_exists(FCPATH . 'uploads/funcionarios/' . $funcionario['photo'])) {
                     unlink(FCPATH . 'uploads/funcionarios/' . $funcionario['photo']);
                }
            }

            // 3. Update Employee Record
            $employeeData = [
                'id'                  => $id,
                'registration_number' => $data['registration_number'],
                'position'            => $data['position'],
                'department'          => $data['department'] ?? null,
                'status'              => isset($data['active']) ? 'active' : 'inactive',
                'is_lawyer'           => isset($data['is_lawyer']) ? 1 : 0,
                'rateio_ativo'        => isset($data['rateio_ativo']) ? 1 : 0,
                'oab_numero'          => $data['oab_numero'] ?? null,
                'oab_uf'              => $data['oab_uf'] ?? null,
                'photo'               => $photoPath,
            ];

            $this->model->save($employeeData);

            $this->db->transComplete();
            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        $funcionario = $this->model->find($id);
        if (!$funcionario) {
            return false;
        }

        $this->db->transStart();
        
        // Soft delete employee
        $this->model->delete($id);
        
        // Delete or Deactivate User?
        // Let's delete the user from Shield to prevent login
        $this->userModel->delete($funcionario['user_id']);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
