<?php

namespace App\Services;

use App\Repositories\Contracts\ProcessoRepositoryInterface;
use Carbon\Carbon;

class ProcessoService
{
    protected $repository;

    public function __construct(ProcessoRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        $data['numero'] = $this->generateProcessNumber();
        $data['data_abertura'] = Carbon::now();
        $data['status'] = 'aberto';
        
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['status']) && $data['status'] === 'concluido') {
            $data['data_fechamento'] = Carbon::now();
        }
        
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
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
