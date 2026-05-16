<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Processo\StoreProcessoRequest;
use App\Http\Requests\Processo\UpdateProcessoStatusRequest;
use App\Services\ProcessoService;
use App\Services\TipoProcessoService;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Class ProcessoController
 * @package App\Http\Controllers\Web
 */
class ProcessoController extends Controller
{
    /**
     * @var ProcessoService
     */
    protected $service;

    /**
     * @var TipoProcessoService
     */
    protected $tipoService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ProcessoController constructor.
     * @param ProcessoService $service
     * @param TipoProcessoService $tipoService
     * @param UserService $userService
     */
    public function __construct(
        ProcessoService $service, 
        TipoProcessoService $tipoService,
        UserService $userService
    ) {
        $this->service = $service;
        $this->tipoService = $tipoService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $processos = $this->service->listAll($request->all());
        $tipos = $this->tipoService->getAllActive();
        return view('processos.index', compact('processos', 'tipos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $tipos = $this->tipoService->getAllActive();
        $usuarios = $this->userService->listAll(['is_active' => true]);
        return view('processos.create', compact('tipos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProcessoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProcessoRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('processos.index')
            ->with('success', 'Processo registrado com sucesso.');
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $processo = $this->service->findById($id);
        $documentosService = app(\App\Services\DocumentoService::class);
        $documentos = $documentosService->listByProcesso($id);
        
        return view('processos.show', compact('processo', 'documentos'));
    }

    /**
     * Update the status of the specified resource.
     * @param UpdateProcessoStatusRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(UpdateProcessoStatusRequest $request, $id)
    {
        $this->service->update($id, $request->validated());

        return back()->with('success', 'Status do processo atualizado.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('processos.index')
            ->with('success', 'Processo excluído com sucesso.');
    }
}
