<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfissionaisRepository;
use CodeIgniter\Database\BaseConnection;
use Exception;

/**
 * Classe de serviço para profissionais
 * 
 * @author Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 * 
 */
class ProfissionaisService extends BaseService
{

    protected BaseConnection $db;

    public function __construct()
    {
        $this->repository = new ProfissionaisRepository();
        $this->db = \Config\Database::connect();
    }


    /**
     * Cria um profissional
     * Sobreescreve o metodo create da classe BaseService e extrai dados complexos
     * 
     * @param array $data Combined data: professional fields + 'address_data' + 'relations'
     */
    public function create(array $data): int
    {
        $this->db->transStart();

        try {
            // Extract auxiliary data
            $addressData = $data['address_data'] ?? [];
            $relations = $data['relations'] ?? [];

            // Remove aux data from main array so it doesn't break model insert
            unset($data['address_data']);
            unset($data['relations']);

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
     * Sobreescreve o metodo update da classe BaseService e extrai dados complexos
     * 
     * @param int $id
     * @param array $data Combined data: professional fields + 'address_data' + 'relations'
     */
    public function update(int $id, array $data): bool
    {
        $this->db->transStart();

        try {
            // Extract auxiliary data
            $addressData = $data['address_data'] ?? [];
            $relations = $data['relations'] ?? [];

            // Remove aux data
            unset($data['address_data']);
            unset($data['relations']);

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

    /**
     * Sincroniza as relações de um profissional
     * 
     * @param int $id
     * @param array $relations
     * @return void
     */
    private function syncAllRelations(int $id, array $relations)
    {
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
    public function getFormOptions(): array
    {
        return [
            'profissoes' => $this->repository->getAllProfissoes(),
            'categorias' => $this->repository->getAllCategorias(),
            'atribuicoes' => $this->repository->getAllAtribuicoes(),
        ];
    }

    /**
     * Retorna todos os profissionais
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }
}
