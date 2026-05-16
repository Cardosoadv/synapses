<?php

namespace App\Services;

use App\Repositories\Contracts\DocumentoRepositoryInterface;
use App\Repositories\Contracts\MovimentacaoRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoService
{
    protected $repository;
    protected $movimentacaoRepository;

    public function __construct(
        DocumentoRepositoryInterface $repository,
        MovimentacaoRepositoryInterface $movimentacaoRepository
    ) {
        $this->repository = $repository;
        $this->movimentacaoRepository = $movimentacaoRepository;
    }

    public function listByProcesso(int $processoId)
    {
        return $this->repository->getByProcesso($processoId);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function findByUuid(string $uuid)
    {
        return $this->repository->findByUuid($uuid);
    }

    public function create(array $data, $file = null)
    {
        if ($file) {
            $path = $file->store('documentos/' . $data['processo_id'], 'local');
            $data['arquivo_path'] = $path;
            $data['tipo_documento'] = 'pdf';
        }

        $data['numero_documento'] = $this->generateDocumentNumber();
        $data['status'] = $data['status'] ?? 'rascunho';
        
        $documento = $this->repository->create($data);

        $this->movimentacaoRepository->create([
            'processo_id' => $data['processo_id'],
            'user_id' => $data['user_id'] ?? auth()->id(),
            'status_anterior' => null,
            'status_novo' => 'documento_adicionado',
            'observacao' => 'Documento "' . ($data['titulo'] ?? 'Sem título') . '" adicionado.'
        ]);

        return $documento;
    }

    public function update(int $id, array $data, $file = null)
    {
        if ($file) {
            $documento = $this->repository->findById($id);
            if ($documento->arquivo_path) {
                Storage::disk('local')->delete($documento->arquivo_path);
            }
            $path = $file->store('documentos/' . $documento->processo_id, 'local');
            $data['arquivo_path'] = $path;
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        $documento = $this->repository->findById($id);
        if ($documento && $documento->arquivo_path) {
            Storage::disk('local')->delete($documento->arquivo_path);
        }
        return $this->repository->delete($id);
    }

    protected function generateDocumentNumber()
    {
        // Simple sequential number for documents
        $latest = \App\Models\Documento::orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        return 'DOC-' . str_pad($nextId, 8, '0', STR_PAD_LEFT);
    }
}
