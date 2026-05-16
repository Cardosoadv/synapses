<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProcessoService;
use App\Services\TipoProcessoService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ProcessoController extends Controller
{
    protected $service;
    protected $tipoService;
    protected $userService;

    public function __construct(
        ProcessoService $service, 
        TipoProcessoService $tipoService,
        UserService $userService
    ) {
        $this->service = $service;
        $this->tipoService = $tipoService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $processos = $this->service->listAll($request->all());
        $tipos = $this->tipoService->getAllActive();
        return view('processos.index', compact('processos', 'tipos'));
    }

    public function create()
    {
        $tipos = $this->tipoService->getAllActive();
        $usuarios = $this->userService->listar(['is_active' => true]); 
        return view('processos.create', compact('tipos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_processo_id' => 'required|exists:tipos_processos,id',
            'interessado_id' => 'nullable|exists:users,id',
            'assunto' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'nivel_acesso' => 'required|in:publico,restrito,sigiloso',
        ]);

        $this->service->create($validated);

        return redirect()->route('processos.index')
            ->with('success', 'Processo registrado com sucesso.');
    }

    public function show($id)
    {
        $processo = $this->service->findById($id);
        $documentosService = app(\App\Services\DocumentoService::class);
        $documentos = $documentosService->listByProcesso($id);
        
        return view('processos.show', compact('processo', 'documentos'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:aberto,em_analise,concluido,arquivado',
        ]);

        $this->service->update($id, $validated);

        return back()->with('success', 'Status do processo atualizado.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('processos.index')
            ->with('success', 'Processo excluído com sucesso.');
    }
}
