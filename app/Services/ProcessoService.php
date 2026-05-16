<?php

namespace App\Services;

use App\Repositories\Contracts\MovimentacaoRepositoryInterface;
use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ProcessoService
 * @package App\Services
 */
class ProcessoService
{
    /**
     * @var ProcessoRepositoryInterface
     */
    protected $repository;

    /**
     * @var MovimentacaoRepositoryInterface
     */
    protected $movimentacaoRepository;

    /**
     * ProcessoService constructor.
     * @param ProcessoRepositoryInterface $repository
     * @param MovimentacaoRepositoryInterface $movimentacaoRepository
     */
    public function __construct(
        ProcessoRepositoryInterface $repository,
        MovimentacaoRepositoryInterface $movimentacaoRepository
    ) {
        $this->repository = $repository;
        $this->movimentacaoRepository = $movimentacaoRepository;
    }

    /**
     * List all processes with optional filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listAll(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    /**
     * Find a process by ID.
     *
     * @param int $id
     * @return \App\Models\Processo|null
     */
    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new process.
     *
     * @param array $data
     * @return \App\Models\Processo
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['numero'] = $this->generateProcessNumber();
            $data['data_abertura'] = Carbon::now();
            $data['status'] = 'aberto';

            $processo = $this->repository->create($data);

            $this->movimentacaoRepository->create([
                'processo_id' => $processo->id,
                'user_id' => Auth::id(),
                'status_anterior' => null,
                'status_novo' => 'aberto',
                'observacao' => 'Abertura do processo.'
            ]);

            return $processo;
        });
    }

    /**
     * Update an existing process.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Processo
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $processo = $this->repository->findById($id);
            $oldStatus = $processo->status;

            if (isset($data['status']) && $data['status'] === 'concluido') {
                $data['data_fechamento'] = Carbon::now();
            }

            $updatedProcesso = $this->repository->update($id, $data);

            if (isset($data['status']) && $data['status'] !== $oldStatus) {
                $this->movimentacaoRepository->create([
                    'processo_id' => $id,
                    'user_id' => Auth::id(),
                    'status_anterior' => $oldStatus,
                    'status_novo' => $data['status'],
                    'observacao' => 'Alteração de status do processo.'
                ]);
            }

            return $updatedProcesso;
        });
    }

    /**
     * Delete a process by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Generate a unique process number.
     *
     * @return string
     */
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
