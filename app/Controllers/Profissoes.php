<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProfissoesService;

/**
 * Controller para gerenciar profissões
 * 
 * @package App\Controllers
 * @author  Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 */
class Profissoes extends BaseController
{
    protected ProfissoesService $service;

    public function __construct()
    {
        $this->service = new ProfissoesService();
    }

    /**
     * Lista todas as profissões
     * 
     * @return view
     */
    public function index()
    {
        $data['itens'] = $this->service->listAll();
        $data['titulo'] = 'Gerenciar Profissões';
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/index', $data);
    }

    /**
     * Formulário para criar uma nova profissão
     * 
     * @return view
     */
    public function novo()
    {
        $data['titulo'] = 'Nova Profissão';
        $data['action'] = base_url('profissoes/salvar');
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/form', $data);
    }

    /**
     * Salva uma nova profissão
     * 
     * @return redirect
     */
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

    /**
     * Formulário para editar uma profissão
     * 
     * @param int $id
     * @return view
     */
    public function editar($id)
    {
        $data['item'] = $this->service->getById($id);
        if (!$data['item']) return redirect()->to('/profissoes');

        $data['titulo'] = 'Editar Profissão';
        $data['action'] = base_url('profissoes/atualizar/' . $id);
        $data['controller'] = 'profissoes';
        return $this->loadView('auxiliares/form', $data);
    }

    /**
     * Atualiza uma profissão
     * 
     * @param int $id
     * @return redirect
     */
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
    
    /**
     * Exclui uma profissão
     * 
     * @param int $id
     * @return redirect
     */
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
