<?php

namespace App\Services;

use App\Models\UsuariosModel;
use App\Repositories\UsuariosRepository;
use CodeIgniter\Shield\Entities\User;

class UsuariosService
{
    protected $repository;
    protected $model;

    public function __construct()
    {
        $this->repository = new UsuariosRepository();
        $this->model = new UsuariosModel();
    }

    public function getUsers(int $perPage = 10, string $search = null)
    {
        return $this->repository->getPaginatedUsers($perPage, $search);
    }

    public function getUserById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data, $file = null)
    {
        // Separate User fields from Identity fields and Groups
        $username = $data['username'];
        $email    = $data['email'];
        $password = $data['password'];
        $groups   = $data['groups'] ?? [];
        $permissions = $data['permissions'] ?? [];

        // Handle File Upload
        $imagePath = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/usuarios', $newName);
            $imagePath = $newName;
        }

        // Prepare User Entity
        $user = new User([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
            'active'   => isset($data['active']) ? 1 : 0,
            // Custom fields
            'is_lawyer'    => isset($data['is_lawyer']) ? 1 : 0,
            'rateio_ativo' => isset($data['rateio_ativo']) ? 1 : 0,
            'oab_numero'   => $data['oab_numero'] ?? null,
            'oab_uf'       => $data['oab_uf'] ?? null,
            'auth_image'   => $imagePath,
        ]);

        // Save User (Shield handles hashing password and creating email identity via hooks if setup, 
        // OR we might need to manually handle it if using custom model without full Shield Controller logic)
        // Shield's User Model has 'saveEmailIdentity' in afterInsert.
        
        $this->model->save($user);
        
        // Fetch the created user to get ID and ensure it's loaded
        $user = $this->model->findById($this->model->getInsertID());

        // Sync Groups
        if (!empty($groups)) {
            $user->syncGroups(...$groups);
        }

        // Sync Permissions
        if (!empty($permissions)) {
            foreach ($permissions as $perm) {
                $user->addPermission($perm);
            }
        }
        
        return $user;
    }

    public function update(int $id, array $data, $file = null)
    {
        $user = $this->model->findById($id);
        if (!$user) {
            return false;
        }

        // Handle File Upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/usuarios', $newName);
            // Delete old file if exists?
            // if ($user->auth_image && file_exists(FCPATH . 'uploads/usuarios/' . $user->auth_image)) {
            //     unlink(FCPATH . 'uploads/usuarios/' . $user->auth_image);
            // }
            $user->auth_image = $newName;
        }

        $user->username = $data['username'];
        
        // Only update password if provided
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->active       = isset($data['active']) ? 1 : 0;
        $user->is_lawyer    = isset($data['is_lawyer']) ? 1 : 0;
        $user->rateio_ativo = isset($data['rateio_ativo']) ? 1 : 0;
        $user->oab_numero   = $data['oab_numero'] ?? null;
        $user->oab_uf       = $data['oab_uf'] ?? null;

        $this->model->save($user);

        // Update Identity (Email)
        // Shield Entity helper for email
        if ($user->email !== $data['email']) {
             // Create Identity if not exists or update.
             // Easier way: 
             $identities = $user->identities; // Get all
             // Find email identity
             // Implementation detail: Shield requires specific handling for identity updates usually.
             // But $user->email = 'new' and save() updates it if using 'saveEmailIdentity' callback?
             // No, saveEmailIdentity is only for insert.
             // We need to update the identity manually.
             
             $identityModel = new \CodeIgniter\Shield\Models\UserIdentityModel();
             $identity = $identityModel->getIdentityByType($user, 'email_password');
             if ($identity) {
                 $identity->secret = $data['email'];
                 $identityModel->save($identity);
             }
        }

        // Groups
        if (isset($data['groups'])) {
            $user->syncGroups(...$data['groups']);
        } else {
             $user->syncGroups(); // Clear groups if none selected
        }

        // Permissions
        // syncPermissions method exists? Shield User Entity has syncPermissions.
        if (isset($data['permissions'])) {
            $user->syncPermissions(...$data['permissions']);
        } else {
            $user->syncPermissions();
        }

        return true;
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
