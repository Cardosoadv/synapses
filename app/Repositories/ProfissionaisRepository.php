<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProfissionaisModel;
use App\Models\EnderecosProfissionaisModel;
use App\Models\ProfissoesModel;
use App\Models\CategoriasProfissionaisModel;
use App\Models\AtribuicoesModel;
use CodeIgniter\Database\BaseBuilder;

class ProfissionaisRepository
{
    protected ProfissionaisModel $profissionaisModel;
    protected EnderecosProfissionaisModel $enderecosModel;
    protected \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->profissionaisModel = new ProfissionaisModel();
        $this->enderecosModel = new EnderecosProfissionaisModel();
        $this->db = \Config\Database::connect();
    }

    public function findAll(int $limit = 0, int $offset = 0): array
    {
        return $this->profissionaisModel->findAll($limit, $offset);
    }

    public function findById(int $id): ?array
    {
        $professional = $this->profissionaisModel->find($id);
        if (!$professional) {
            return null;
        }

        // Fetch Address
        $professional['endereco'] = $this->enderecosModel->where('profissional_id', $id)->first();

        // Fetch Related Data (Professions, Categories, Attributions)
        $professional['profissoes'] = $this->getRelatedIds($id, 'profissional_profissoes', 'profissao_id');
        $professional['categorias'] = $this->getRelatedIds($id, 'profissional_categorias', 'categoria_id');
        $professional['atribuicoes'] = $this->getRelatedIds($id, 'profissional_atribuicoes', 'atribuicao_id');

        return $professional;
    }

    protected function getRelatedIds(int $professionalId, string $table, string $fkColumn): array
    {
        return $this->db->table($table)
            ->select($fkColumn)
            ->where('profissional_id', $professionalId)
            ->get()
            ->getResultArray();
    }
    
    public function getRelatedNames(int $professionalId, string $pivotTable, string $relatedTable, string $fkPivot, string $nameCol = 'nome'): array
    {
         return $this->db->table($pivotTable)
            ->select("$relatedTable.$nameCol")
            ->join($relatedTable, "$relatedTable.id = $pivotTable.$fkPivot")
            ->where("$pivotTable.profissional_id", $professionalId)
            ->get()
            ->getResultArray();
    }

    public function create(array $data): int
    {
        return (int) $this->profissionaisModel->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->profissionaisModel->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->profissionaisModel->delete($id);
    }

    // --- Auxiliary Tables Management ---

    public function saveAddress(int $professionalId, array $data): void
    {
        $existing = $this->enderecosModel->where('profissional_id', $professionalId)->first();
        $data['profissional_id'] = $professionalId;

        if ($existing) {
            $this->enderecosModel->update($existing['id'], $data);
        } else {
            $this->enderecosModel->insert($data);
        }
    }

    public function syncRelations(int $professionalId, string $pivotTable, string $fkColumn, array $ids): void
    {
        $builder = $this->db->table($pivotTable);
        
        // Remove existing
        $builder->where('profissional_id', $professionalId)->delete();

        // Add new
        if (!empty($ids)) {
            $batch = [];
            foreach ($ids as $id) {
                // Ignore invalid/empty IDs
                if(empty($id)) continue;
                
                $batch[] = [
                    'profissional_id' => $professionalId,
                    $fkColumn         => $id,
                ];
            }
            if (!empty($batch)) {
                $builder->insertBatch($batch);
            }
        }
    }
    
    // --- Dropdown Helpers ---
    public function getAllProfissoes(): array {
        return (new ProfissoesModel())->findAll();
    }

    public function getAllCategorias(): array {
        return (new CategoriasProfissionaisModel())->findAll();
    }

    public function getAllAtribuicoes(): array {
        return (new AtribuicoesModel())->findAll();
    }
}
