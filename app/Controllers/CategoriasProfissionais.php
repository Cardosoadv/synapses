<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\CategoriasProfissionaisService;

class CategoriasProfissionais extends BaseController
{
    protected CategoriasProfissionaisService $service;

    public function __construct()
    {
        $this->service = new CategoriasProfissionaisService();
    }

    public function index()
    {
        $data['itens'] = $this->service->listAll();
        $data['title'] = 'Gerenciar Categorias';
        $data['controller'] = 'categorias_profissionais';
        return $this->loadView('auxiliares/index', $data);
    }

    public function novo()
    {
        $data['title'] = 'Nova Categoria';
        $data['action'] = base_url('categorias_profissionais/salvar');
        $data['controller'] = 'categorias_profissionais';
        return $this->loadView('auxiliares/form', $data);
    }

    public function salvar()
    {
        if (!$this->validate(['nome' => 'required|min_length[3]|is_unique[categorias_profissionais.nome]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->create($this->request->getPost());
            return redirect()->to('/categorias_profissionais')->with('message', 'Registro criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editar($id)
    {
        $data['item'] = $this->service->getById($id);
        if (!$data['item']) return redirect()->to('/categorias_profissionais');

        $data['title'] = 'Editar Categoria';
        $data['action'] = base_url('categorias_profissionais/atualizar/' . $id);
        $data['controller'] = 'categorias_profissionais';
        return $this->loadView('auxiliares/form', $data);
    }

    public function atualizar($id)
    {
        if (!$this->validate(['nome' => "required|min_length[3]|is_unique[categorias_profissionais.nome,id,$id]"])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->update($id, $this->request->getPost());
            return redirect()->to('/categorias_profissionais')->with('message', 'Registro atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function excluir($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->to('/categorias_profissionais')->with('message', 'Registro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
