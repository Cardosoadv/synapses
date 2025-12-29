<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\UsuariosService;
use App\Services\FuncionariosService;

class Usuarios extends BaseController
{
    protected $service;
    protected $funcionariosService; 

    public function __construct()
    {
        $this->service = new UsuariosService();
        $this->funcionariosService = new FuncionariosService();
    }

    public function index()
    {
        $search = $this->request->getGet('s');
        $data = $this->service->getUsers(10, $search);
        
        // Pass search term back to view
        $data['search'] = $search;
        
        return $this->loadView('usuarios/index', $data);
    }

    public function novo()
    {
        return $this->loadView('usuarios/form');
    }

    public function salvar()
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto-perfil');

        // Validation? Service creates User entity which might throw exception or false.
        // Ideally we use $this->validate() here.
        // For brevity, assuming basic success or minimal error handling.
        
        try {
            $this->service->create($data, $file);
            return redirect()->to('/usuarios')->with('message', 'Usuário criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        $user = $this->service->getUserById($id);
        if (!$user) {
            return redirect()->to('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        return $this->loadView('usuarios/form', ['user' => $user]);
    }

    public function atualizar($id)
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto-perfil');

        try {
            $this->service->update($id, $data, $file);
            return redirect()->to('/usuarios')->with('message', 'Usuário atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    public function excluir($id)
    {
        $this->service->delete($id);
        return redirect()->to('/usuarios')->with('message', 'Usuário excluído com sucesso.');
    }

    public function grupos()
    {
        $config = config('AuthGroups');

        $data = [
            'groups'      => $config->groups,
            'permissions' => $config->permissions,
            'matrix'      => $config->matrix,
        ];

        return $this->loadView('usuarios/grupos', $data);
    }

    public function exibirFoto($id)
    {
        $funcionario = $this->funcionariosService->getFuncionarioByUserId($id);
        if (!$funcionario || empty($funcionario['photo'])) {
            // Return default placeholder or 404
            return $this->response->setStatusCode(404);
        }

        $path =     WRITEPATH . 'uploads/funcionarios/'.$funcionario['id'].'/'.$funcionario['photo'];
        if (file_exists($path)) {
            $mime = mime_content_type($path);
            return $this->response->setHeader('Content-Type', $mime)
                                  ->setBody(file_get_contents($path));
        }

        return $this->response->setStatusCode(404);
    }

    public function promover($id)
    {
        $user = $this->service->getUserById($id);
        if (!$user) {
            return redirect()->to('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        // Check if already employee
        if ($this->funcionariosService->getFuncionarioByUserId($id)) {
             return redirect()->to('/usuarios')->with('warning', 'Este usuário já é um funcionário.');
        }

        return $this->loadView('usuarios/promover', ['user' => $user]);
    }

    public function salvarPromocao($id)
    {
        $user = $this->service->getUserById($id);
        if (!$user) {
            return redirect()->to('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        $data = $this->request->getPost();

        try {
            $this->funcionariosService->createFromUser($id, $data);
            return redirect()->to('/usuarios')->with('message', 'Usuário promovido a funcionário com sucesso.');
        } catch (\Exception $e) {
             return redirect()->back()->withInput()->with('error', 'Erro ao promover usuário: ' . $e->getMessage());
        }
    }
}
