<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfissionaisRepository;
use CodeIgniter\Database\BaseConnection;
use Exception;

class ProfissionaisService extends BaseService
{
    protected ProfissionaisRepository $repository;
    protected BaseConnection $db;

    public function __construct()
    {
        $this->repository = new ProfissionaisRepository();
        $this->db = \Config\Database::connect();
    }

    /**
     * Cria um profissional
     * Sobreescreve o metodo create da classe BaseService
     * 
     * @param array $data Main professional data
     * @param array $addressData Address data
     * @param array $relations Arrays of IDs for 'profissoes', 'categorias', 'atribuicoes'
     */
    public function create(array $data, array $addressData, array $relations): int
    {
        $this->db->transStart();

        try {
            // 1. Create Professional
            $id = $this->repository->create($data);
            if (!$id) {
                throw new Exception("Falha ao criar profissional.");
            }

            // 2. Save Address
            if (!empty($addressData)) {
                $this->repository->saveAddress($id, $addressData);
            }

            // 3. Sync Relations
            $this->syncAllRelations($id, $relations);

            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                 throw new Exception("Erro na transação de banco de dados.");
            }

            return $id;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Atualiza um profissional
     * Sobreescreve o metodo update da classe BaseService
     * 
     * @param int $id
     * @param array $data Main professional data
     * @param array $addressData Address data
     * @param array $relations Arrays of IDs for 'profissoes', 'categorias', 'atribuicoes'
     */
    public function update(int $id, array $data, array $addressData, array $relations): bool
    {
        $this->db->transStart();

        try {
            // 1. Update Professional
            $this->repository->update($id, $data);

            // 2. Save Address
            if (!empty($addressData)) {
                $this->repository->saveAddress($id, $addressData);
            }

            // 3. Sync Relations
            $this->syncAllRelations($id, $relations);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                 return false;
            }

            return true;
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
    
    private function syncAllRelations(int $id, array $relations) {
        if (isset($relations['profissoes'])) {
            $this->repository->syncRelations($id, 'profissional_profissoes', 'profissao_id', $relations['profissoes']);
        }
        if (isset($relations['categorias'])) {
            $this->repository->syncRelations($id, 'profissional_categorias', 'categoria_id', $relations['categorias']);
        }
        if (isset($relations['atribuicoes'])) {
            $this->repository->syncRelations($id, 'profissional_atribuicoes', 'atribuicao_id', $relations['atribuicoes']);
        }
    }
    
    // Aux helpers for view
    public function getFormOptions(): array {
        return [
            'profissoes' => $this->repository->getAllProfissoes(),
            'categorias' => $this->repository->getAllCategorias(),
            'atribuicoes' => $this->repository->getAllAtribuicoes(),
        ];
    }
}
