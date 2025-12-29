<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AtribuicoesService;

class Atribuicoes extends BaseController
{
    protected AtribuicoesService $service;

    public function __construct()
    {
        $this->service = new AtribuicoesService();
    }

    public function index()
    {
        $data['itens'] = $this->service->listAll();
        $data['title'] = 'Gerenciar Atribuições';
        $data['controller'] = 'atribuicoes';
        return $this->loadView('auxiliares/index', $data);
    }

    public function novo()
    {
        $data['title'] = 'Nova Atribuição';
        $data['action'] = base_url('atribuicoes/salvar');
        $data['controller'] = 'atribuicoes';
        return $this->loadView('auxiliares/form', $data);
    }

    public function salvar()
    {
        if (!$this->validate(['nome' => 'required|min_length[3]|is_unique[atribuicoes.nome]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->create($this->request->getPost());
            return redirect()->to('/atribuicoes')->with('message', 'Registro criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editar($id)
    {
        $data['item'] = $this->service->getById($id);
        if (!$data['item']) return redirect()->to('/atribuicoes');

        $data['title'] = 'Editar Atribuição';
        $data['action'] = base_url('atribuicoes/atualizar/' . $id);
        $data['controller'] = 'atribuicoes';
        return $this->loadView('auxiliares/form', $data);
    }

    public function atualizar($id)
    {
        if (!$this->validate(['nome' => "required|min_length[3]|is_unique[atribuicoes.nome,id,$id]"])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->service->update($id, $this->request->getPost());
            return redirect()->to('/atribuicoes')->with('message', 'Registro atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function excluir($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->to('/atribuicoes')->with('message', 'Registro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
