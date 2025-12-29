<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ProfissionaisService;

class Profissionais extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new ProfissionaisService();
    }

    public function index()
    {
        $data['profissionais'] = $this->service->listAll();
        return $this->loadView('profissionais/index', $data);
    }

    public function novo()
    {
        $data = $this->service->getFormOptions();
        return $this->loadView('profissionais/form', $data);
    }

    public function salvar()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'cpf'  => 'required|exact_length[14]|is_unique[professionals.cpf]',
            // Add other rules as needed
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        // Separate data
        $professionalData = [
            'name'                => $post['name'],
            'cpf'                 => $post['cpf'],
            'registration_number' => $post['registration_number'] ?? null,
            'sei_process'         => $post['sei_process'] ?? null,
            'status'              => $post['status'] ?? 'pending',
            'user_id'             => !empty($post['user_id']) ? $post['user_id'] : null,
        ];

        $addressData = [
            'cep'         => $post['cep'] ?? null,
            'logradouro'  => $post['logradouro'] ?? null,
            'numero'      => $post['numero'] ?? null,
            'complemento' => $post['complemento'] ?? null,
            'bairro'      => $post['bairro'] ?? null,
            'cidade'      => $post['cidade'] ?? null,
            'uf'          => $post['uf'] ?? null,
        ];

        $relations = [
            'profissoes'  => $post['profissoes'] ?? [],
            'categorias'  => $post['categorias'] ?? [],
            'atribuicoes' => $post['atribuicoes'] ?? [],
        ];

        try {
            // Unify data for service (to match BaseService signature)
            $professionalData['address_data'] = $addressData;
            $professionalData['relations'] = $relations;

            $this->service->create($professionalData);
            return redirect()->to('/profissionais')->with('message', 'Profissional cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        $data = $this->service->getFormOptions();
        $professional = $this->service->getById($id);

        if (!$professional) {
            return redirect()->to('/profissionais')->with('error', 'Profissional não encontrado.');
        }

        $data['profissional'] = $professional;
        return $this->loadView('profissionais/form', $data);
    }

    public function atualizar($id)
    {
        // Validation (Ignore unique CPF for current user)
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'cpf'  => "required|exact_length[14]|is_unique[professionals.cpf,id,$id]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        $professionalData = [
            'name'                => $post['name'],
            'cpf'                 => $post['cpf'],
            'registration_number' => $post['registration_number'] ?? null,
            'sei_process'         => $post['sei_process'] ?? null,
            'status'              => $post['status'] ?? 'pending',
             'user_id'             => !empty($post['user_id']) ? $post['user_id'] : null,
        ];

        $addressData = [
            'cep'         => $post['cep'] ?? null,
            'logradouro'  => $post['logradouro'] ?? null,
            'numero'      => $post['numero'] ?? null,
            'complemento' => $post['complemento'] ?? null,
            'bairro'      => $post['bairro'] ?? null,
            'cidade'      => $post['cidade'] ?? null,
            'uf'          => $post['uf'] ?? null,
        ];

        $relations = [
            'profissoes'  => $post['profissoes'] ?? [],
            'categorias'  => $post['categorias'] ?? [],
            'atribuicoes' => $post['atribuicoes'] ?? [],
        ];

        try {
            // Unify data for service
            $professionalData['address_data'] = $addressData;
            $professionalData['relations'] = $relations;

            $this->service->update($id, $professionalData);
            return redirect()->to('/profissionais')->with('message', 'Profissional atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    public function excluir($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->to('/profissionais')->with('message', 'Profissional excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }

    public function history($id)
    {
        $professional = $this->service->getById($id);
        if (!$professional) {
             return redirect()->to('/profissionais')->with('error', 'Profissional não encontrado.');
        }

        $data['profissional'] = $professional;
        return $this->loadView('profissionais/history', $data);
    }
}
