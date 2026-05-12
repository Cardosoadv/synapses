<?php

namespace App\Services;

use App\Repositories\Contracts\ProcessoRepositoryInterface;
use App\Repositories\Contracts\MovimentacaoRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessoService
{
    protected $repository;
    protected $movimentacaoRepository;

    public function __construct(
        ProcessoRepositoryInterface $repository,
        MovimentacaoRepositoryInterface $movimentacaoRepository
    ) {
        $this->repository = $repository;
        $this->movimentacaoRepository = $movimentacaoRepository;
    }

    public function listAll(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['numero'] = $this->generateProcessNumber();
            $data['data_abertura'] = Carbon::now();
            $data['status'] = 'aberto';

            $processo = $this->repository->create($data);

            $this->movimentacaoRepository->create([
                'processo_id' => $processo->id,
                'user_id' => Auth::id() ?? $data['interessado_id'] ?? $this->getSystemUserId(),
                'descricao' => 'Abertura do processo',
                'status_atual' => 'aberto',
                'observacoes' => 'Processo criado no sistema.',
            ]);

            return $processo;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $processo = $this->repository->findById($id);
            $statusAnterior = $processo->status;

            if (isset($data['status']) && $data['status'] === 'concluido') {
                $data['data_fechamento'] = Carbon::now();
            }

            $updatedProcesso = $this->repository->update($id, $data);

            if (isset($data['status']) && $data['status'] !== $statusAnterior) {
                $this->movimentacaoRepository->create([
                    'processo_id' => $id,
                    'user_id' => Auth::id() ?? $this->getSystemUserId(),
                    'descricao' => "Alteração de status de {$statusAnterior} para {$data['status']}",
                    'status_anterior' => $statusAnterior,
                    'status_atual' => $data['status'],
                ]);
            }

            return $updatedProcesso;
        });
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Retorna um ID de usuário do sistema para movimentações automatizadas.
     */
    protected function getSystemUserId(): int
    {
        // Tenta encontrar um admin ou o primeiro usuário, caso contrário retorna 1
        return \App\Models\User::where('role', 'admin')->first()?->id ?? 1;
    }

    protected function generateProcessNumber()
    {
        $year = Carbon::now()->year;
        $latestNumber = $this->repository->getLatestProcessNumber($year);
        
        $sequence = 1;
        if ($latestNumber) {
            // Extract sequence from NNNNN.NNNNNN/YYYY-DD
            $parts = explode('.', $latestNumber);
            if (count($parts) > 1) {
                $subparts = explode('/', $parts[1]);
                $sequence = (int)$subparts[0] + 1;
            }
        }

        $organ = '00001';
        $formattedSeq = str_pad($sequence, 6, '0', STR_PAD_LEFT);
        $checkDigit = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT); // Simplified check digit

        return "{$organ}.{$formattedSeq}/{$year}-{$checkDigit}";
    }
}
