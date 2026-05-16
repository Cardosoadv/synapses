<?php

namespace App\Services;

use App\Repositories\Contracts\DocumentoRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoService
{
    protected $repository;

    public function __construct(DocumentoRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        
        return $this->repository->create($data);
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
