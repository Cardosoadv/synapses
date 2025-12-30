<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\EmpresasRepository;
use App\Repositories\EmpresaProfissionalRepository;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use Exception;

class EmpresasService
{
    private EmpresasRepository $repository;
    private EmpresaProfissionalRepository $linkRepository;
    private BaseConnection $db;

    public function __construct()
    {
        $this->repository = new EmpresasRepository();
        $this->linkRepository = new EmpresaProfissionalRepository();
        $this->db = Database::connect();
    }

    public function getAllEmpresas(int $perPage = 10): array
    {
        return $this->repository->paginate($perPage);
    }

    public function getPager()
    {
        return $this->repository->getPager();
    }

    public function getEmpresaById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function createEmpresa(array $data): int
    {
        $this->db->transStart();

        try {
            // Remove mask from CNPJ before saving
            if (isset($data['cnpj'])) {
                $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
            }

            $id = $this->repository->create($data);

            if ($id === false) {
                throw new Exception('Erro ao cadastrar empresa.');
            }

            $this->db->transComplete();
            return $id;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function updateEmpresa(int $id, array $data): bool
    {
        $this->db->transStart();

        try {
            // Remove mask from CNPJ
            if (isset($data['cnpj'])) {
                $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
            }

            if (!$this->repository->update($id, $data)) {
                throw new Exception('Erro ao atualizar empresa.');
            }

            $this->db->transComplete();
            return true;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function deleteEmpresa(int $id): bool
    {
        $this->db->transStart();
        try {
            if (!$this->repository->delete($id)) {
                throw new Exception('Erro ao deletar empresa.');
            }
            $this->db->transComplete();
            return true;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function vincularProfissional(array $data): int
    {
        $this->db->transStart();
        try {
            // Validation logic can be extended here if needed

            $id = $this->linkRepository->link($data);

            if ($id === false) {
                throw new Exception('Erro ao vincular profissional.');
            }

            $this->db->transComplete();
            return $id;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function getAllMotivos(): array
    {
        return $this->linkRepository->getAllMotivos();
    }
}
