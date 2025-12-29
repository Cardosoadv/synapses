<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\FuncionariosService;

class Funcionarios extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new FuncionariosService();
    }

    public function index()
    {
        $search = $this->request->getGet('s');
        $data = $this->service->getAll(10, $search);
        
        $data['search'] = $search;
        $data['titulo'] = 'Gestão de Funcionários';

        // Using loadView from BaseController
        return $this->loadView('funcionarios/index', $data);
    }

    public function novo()
    {
        $data = [
            'titulo' => 'Novo Funcionário',
        ];
        return $this->loadView('funcionarios/form', $data);
    }

    public function salvar()
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto-perfil');

        try {
            $this->service->create($data, $file);
            return redirect()->to('/funcionarios')->with('message', 'Funcionário cadastrado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar funcionário: ' . $e->getMessage());
        }
    }

    protected function validateForm($data)
    {
        $rules = [
            'email' => 'required|valid_email',
            'cpf' => 'required|valid_cpf',
        ];

        $errors = [
            
            'email' => [
                'required' => 'O campo email é obrigatório.',
                'valid_email' => 'O campo email deve ser um email válido.',
            ],
            'cpf' => [
                'required' => 'O campo CPF é obrigatório.',
                'valid_cpf' => 'O campo CPF deve ser um CPF válido.',
            ],
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $errors);

        if (!$validation->run($data)) {
            log_message('error', 'Erro na validação de dados do funcionário: ' . json_encode($validation->getErrors()));
            return $validation->getErrors();
        }

        return true;
    }

    public function editar($id)
    {
        $funcionario = $this->service->getById($id);
        
        if (!$funcionario) {
            return redirect()->to('/funcionarios')->with('error', 'Funcionário não encontrado.');
        }

        $data = [
            'titulo'      => 'Editar Funcionário',
            'funcionario' => $funcionario
        ];

        return $this->loadView('funcionarios/form', $data);
    }

    public function atualizar($id)
    {
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto-perfil');
        $errors = $this->validateForm($data);

        if ($errors) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        try {
            $this->service->update($id, $data, $file);
            return redirect()->to('/funcionarios')->with('message', 'Funcionário atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar funcionário: ' . $e->getMessage());
        }
    }

    public function excluir($id)
    {
        if ($this->service->delete($id)) {
            return redirect()->to('/funcionarios')->with('message', 'Funcionário excluído com sucesso.');
        }
        return redirect()->to('/funcionarios')->with('error', 'Erro ao excluir funcionário.');
    }

    public function exibirFoto($id)
    {
        $funcionario = $this->service->getById($id);
        
        if (!$funcionario || empty($funcionario['photo'])) {
            return $this->response->setStatusCode(404);
        }

        $path = WRITEPATH . 'uploads/funcionarios/'.$id.'/'.$funcionario['photo'];
        
        if (file_exists($path)) {
            $mime = mime_content_type($path);
            return $this->response->setHeader('Content-Type', $mime)
                                  ->setBody(file_get_contents($path));
        }

        return $this->response->setStatusCode(404);
    }
}
