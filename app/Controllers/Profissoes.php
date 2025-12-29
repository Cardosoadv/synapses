<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProfissoesService;

class Profissoes extends BaseController
{
    protected ProfissoesService $service;

    public function __construct()
    {
        $this->service = new ProfissoesService();
        $this->dadosTemplate['titulo'] = 'Profissões'; // Override title if needed or rely on view
    }

    public function index()
    {
        $data['itens'] = $this->service->listAll();
        $data['title'] = 'Gerenciar Profissões';
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/index', $data);
    }

    public function novo()
    {
        $data['title'] = 'Nova Profissão';
        $data['action'] = base_url('profissoes/salvar');
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/form', $data);
    }

    public function salvar()
    {
        if (!$this->validate(['nome' => 'required|min_length[3]|is_unique[profissoes.nome]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->create($this->request->getPost());
            return redirect()->to('/profissoes')->with('message', 'Registro criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editar($id)
    {
        $data['item'] = $this->service->getById($id);
        if (!$data['item']) return redirect()->to('/profissoes');

        $data['title'] = 'Editar Profissão';
        $data['action'] = base_url('profissoes/atualizar/' . $id);
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/form', $data);
    }

    public function atualizar($id)
    {
        if (!$this->validate(['nome' => "required|min_length[3]|is_unique[profissoes.nome,id,$id]"])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->update($id, $this->request->getPost());
            return redirect()->to('/profissoes')->with('message', 'Registro atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function excluir($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->to('/profissoes')->with('message', 'Registro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
