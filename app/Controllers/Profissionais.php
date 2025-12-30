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

            // 1. Create Professional first to get ID
            $id = $this->service->create($professionalData);

            // 2. Handle file uploads using the new ID
            $filesData = $this->handleUploads($id);

            // 3. Update record if files were uploaded
            if (!empty($filesData)) {
                $this->service->update($id, $filesData);
            }

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

            // 1. Handle file uploads using existing ID
            $filesData = $this->handleUploads($id);

            // Merge files into main data
            if (!empty($filesData)) {
                $professionalData = array_merge($professionalData, $filesData);
            }

            // 2. Update Professional
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

    /**
     * Serve arquivos de biometria de forma segura
     */
    public function showFile(int $id, string $filename)
    {
        $path = WRITEPATH . "uploads/profissionais/$id/$filename";

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }

    /**
     * Handles file uploads for photo, fingerprint, and signature
     * Organized by Professional ID in WRITEPATH
     */
    private function handleUploads(int $id): array
    {
        $uploadedData = [];
        $files = ['photo', 'fingerprint', 'signature'];
        $uploadPath = WRITEPATH . "uploads/profissionais/$id/";

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $uploadedData[$field] = $newName;
            }
        }
        return $uploadedData;
    }
}
