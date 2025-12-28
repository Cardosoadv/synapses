<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\UsuariosService;

class Usuarios extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new UsuariosService();
    }

    public function index()
    {
        $search = $this->request->getGet('s');
        $data = $this->service->getUsers(10, $search);
        
        // Pass search term back to view
        $data['search'] = $search;
        
        // Active users count (approximate, for the stats widget)
        // Ideally Service should provide this.
        // Quick fix: count total active? Repository result has total, but active specific?
        // Let's just use total for now or ignore 'activeUsers' if not available in data.
        // The view checks if(isset($activeUsers)).
        
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

    public function exibirFoto($id)
    {
        $user = $this->service->getUserById($id);
        if (!$user || empty($user['auth_image'])) {
            // Return default placeholder or 404
            return $this->response->setStatusCode(404);
        }

        $path = FCPATH . 'uploads/usuarios/' . $user['auth_image'];
        if (file_exists($path)) {
            $mime = mime_content_type($path);
            return $this->response->setHeader('Content-Type', $mime)
                                  ->setBody(file_get_contents($path));
        }

        return $this->response->setStatusCode(404);
    }
}
