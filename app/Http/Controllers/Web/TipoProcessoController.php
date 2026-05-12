<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoProcesso\StoreTipoProcessoRequest;
use App\Http\Requests\TipoProcesso\UpdateTipoProcessoRequest;
use App\Services\TipoProcessoService;
use Illuminate\Http\Request;

/**
 * Class TipoProcessoController
 * @package App\Http\Controllers\Web
 */
class TipoProcessoController extends Controller
{
    /**
     * @var TipoProcessoService
     */
    protected $service;

    /**
     * TipoProcessoController constructor.
     * @param TipoProcessoService $service
     */
    public function __construct(TipoProcessoService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tipos = $this->service->listAll($request->all());
        return view('tipos_processos.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('tipos_processos.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTipoProcessoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTipoProcessoRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo criado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $tipo = $this->service->findById($id);
        return view('tipos_processos.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateTipoProcessoRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTipoProcessoRequest $request, $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo excluído com sucesso.');
    }
}
