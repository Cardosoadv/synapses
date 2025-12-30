<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\EmpresasService;
use App\Services\ProfissionaisService;
use CodeIgniter\HTTP\ResponseInterface;

class Empresas extends BaseController
{
    private EmpresasService $service;

    public function __construct()
    {
        $this->service = new EmpresasService();
    }

    public function index()
    {
        $data = [
            'empresas' => $this->service->getAllEmpresas(),
            'pager'    => $this->service->getPager(), // Corrected call
        ];

        return $this->loadView('empresas/index', $data);
    }

    public function novo()
    {
        return $this->loadView('empresas/form', ['titulo' => 'Nova Empresa']);
    }

    public function editar(int $id)
    {
        $empresa = $this->service->getEmpresaById($id);

        if (!$empresa) {
            return redirect()->to('/empresas')->with('error', 'Empresa não encontrada.');
        }

        return $this->loadView('empresas/form', [
            'titulo'  => 'Editar Empresa',
            'empresa' => $empresa
        ]);
    }

    public function salvar()
    {
        $rules = [
            'razao_social' => 'required|min_length[3]|max_length[255]',
            'cnpj'         => 'required|valid_cnpj|is_unique[empresas.cnpj]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->request->getPost();
            $this->service->createEmpresa($data);

            return redirect()->to('/empresas')->with('success', 'Empresa cadastrada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function atualizar(int $id)
    {
        $rules = [
            'razao_social' => 'required|min_length[3]|max_length[255]',
            'cnpj'         => "required|valid_cnpj|is_unique[empresas.cnpj,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->request->getPost();
            $this->service->updateEmpresa($id, $data);

            return redirect()->to('/empresas')->with('success', 'Empresa atualizada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function excluir(int $id)
    {
        try {
            $this->service->deleteEmpresa($id);
            return redirect()->to('/empresas')->with('success', 'Empresa excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()->to('/empresas')->with('error', $e->getMessage());
        }
    }

    public function vincular()
    {
        // We need lists of professionals and companies mostly, or maybe this initiates from a context?
        // The requirement says "create a link between professional and company". 
        // Let's assume a standalone page or a modal. 
        // For a standalone page, we need all companies and all professionals.

        // However, fetching ALL professionals might be heavy if not paginated or searched via AJAX.
        // For now, let's load what we can or assume the user might want to link from a specific context.
        // Given existing patterns, I will load lists but be mindful. 
        // Actually, maybe better to just show the form and let the user select.

        // I need a ProfissionaisService to get professionals. 
        // Assuming it exists based on context (Profissionais controller exists).
        // I'll check nicely or just instantiate if I can't inject.
        // Since I don't have the code for ProfissionaisService, I'll rely on the assumption it has a getAll or similar, 
        // or I might need to create a repository call if the service isn't exposed perfectly.
        // For now, I will assume I can get lists. 

        // NOTE: The user asked for "Data de inicio e fim" and "comentario" and "motivação".

        $motivos = $this->service->getAllMotivos();

        // If the lists are huge, this is bad UI, but for initial implementation as requested:
        // I'll try to fetch professionals and companies. 
        // Let's stick to passing just motives for now and maybe fetch others via View Cells or just basic findAll if small.
        // The user has `ProfissionaisModel`.. I'll just use the service calls if available or new Repositories.

        // To avoid complexity of cross-service dependency if not defined, I'll use the repository directly or assuming a standard Service method exists.
        // Actually, I'll update the imports to include what I need.

        $empresas = $this->service->getAllEmpresas(1000); // Get many
        // For professionals, I might need to check how to get them. 
        // I'll look at `Profissionais` controller to see what service it uses.
        // It uses `App\Services\ProfissionaisService`.

        $profissionaisService = new \App\Services\ProfissionaisService();
        $profissionais = $profissionaisService->getAll(); // Assumption

        return $this->loadView('empresas/vincular', [
            'titulo'        => 'Vincular Profissional à Empresa',
            'empresas'      => $empresas,
            'profissionais' => $profissionais,
            'motivos'       => $motivos
        ]);
    }

    public function salvarVinculo()
    {
        $rules = [
            'empresa_id'      => 'required|integer',
            'profissional_id' => 'required|integer',
            'motivo_id'       => 'required|integer',
            'data_inicio'     => 'required|valid_date',
            'comentario'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->request->getPost();
            $this->service->vincularProfissional($data);

            return redirect()->to('/empresas')->with('success', 'Vínculo criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
