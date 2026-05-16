<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DocumentoService;
use App\Services\ProcessoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    protected $service;
    protected $processoService;

    public function __construct(DocumentoService $service, ProcessoService $processoService)
    {
        $this->service = $service;
        $this->processoService = $processoService;
    }

    public function create($processoId)
    {
        $processo = $this->processoService->findById($processoId);
        return view('documentos.create', compact('processo'));
    }

    public function store(Request $request, $processoId)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'arquivo' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'nivel_acesso' => 'required|in:publico,restrito,sigiloso',
        ]);

        $data = [
            'processo_id' => $processoId,
            'user_id' => Auth::id(),
            'titulo' => $validated['titulo'],
            'nivel_acesso' => $validated['nivel_acesso'],
            'status' => 'assinado', // Initial PDFs are usually treated as final/signed
        ];

        $this->service->create($data, $request->file('arquivo'));

        return redirect()->route('processos.show', $processoId)
            ->with('success', 'Documento incluído com sucesso.');
    }

    public function download($uuid)
    {
        $documento = $this->service->findByUuid($uuid);
        
        if (!$documento || !$documento->arquivo_path) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        if (!Storage::disk('local')->exists($documento->arquivo_path)) {
            return back()->with('error', 'O arquivo físico não foi encontrado no servidor.');
        }

        return Storage::disk('local')->download($documento->arquivo_path, $documento->titulo . '.pdf');
    }

    public function view($uuid)
    {
        $documento = $this->service->findByUuid($uuid);
        
        if (!$documento || !$documento->arquivo_path) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        if (!Storage::disk('local')->exists($documento->arquivo_path)) {
            return back()->with('error', 'O arquivo físico não foi encontrado no servidor.');
        }

        return response()->file(storage_path('app/' . $documento->arquivo_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documento->titulo . '.pdf"'
        ]);
    }

    public function viewer($uuid)
    {
        $documentoAtual = $this->service->findByUuid($uuid);
        
        if (!$documentoAtual) {
            abort(404, 'Documento não encontrado.');
        }

        $processo = $documentoAtual->processo;
        $documentos = $this->service->listByProcesso($processo->id);

        return view('documentos.viewer', compact('processo', 'documentos', 'documentoAtual'));
    }

    public function destroy($uuid)
    {
        $documento = $this->service->findByUuid($uuid);
        
        if (!$documento) {
            return back()->with('error', 'Documento não encontrado.');
        }

        $processoId = $documento->processo_id;
        
        $this->service->delete($documento->id);

        return redirect()->route('processos.show', $processoId)
            ->with('success', 'Documento removido com sucesso.');
    }
}
