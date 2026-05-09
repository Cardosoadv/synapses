<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TipoProcessoService;
use Illuminate\Http\Request;

class TipoProcessoController extends Controller
{
    protected $service;

    public function __construct(TipoProcessoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $tipos = $this->service->listAll($request->all());
        return view('tipos_processos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_processos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:tipos_processos,nome',
            'descricao' => 'nullable|string',
            'prefixo' => 'nullable|string|max:10',
            'prazo_conclusao' => 'nullable|integer|min:1',
        ]);

        $this->service->create($validated);

        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo criado com sucesso.');
    }

    public function edit($id)
    {
        $tipo = $this->service->findById($id);
        return view('tipos_processos.edit', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => "required|string|unique:tipos_processos,nome,{$id}",
            'descricao' => 'nullable|string',
            'prefixo' => 'nullable|string|max:10',
            'prazo_conclusao' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $this->service->update($id, $validated);

        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('tipos-processos.index')
            ->with('success', 'Tipo de processo excluído com sucesso.');
    }
}
