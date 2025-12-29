<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProfissionaisModel;
use App\Models\EnderecosProfissionaisModel;
use App\Models\ProfissoesModel;
use App\Models\CategoriasProfissionaisModel;
use App\Models\AtribuicoesModel;
use CodeIgniter\Database\BaseBuilder;

/**
 * Classe de repositório para profissionais
 * 
 * @author Cardoso <fabianocardoso.adv@gmail.com>
 * @version 0.0.1
 * 
 * @property ProfissionaisModel $profissionaisModel
 * @property EnderecosProfissionaisModel $enderecosModel
 * @property \CodeIgniter\Database\BaseConnection $db
 */
class ProfissionaisRepository extends BaseRepository
{
    protected ProfissionaisModel $profissionaisModel;
    protected EnderecosProfissionaisModel $enderecosModel;
    protected \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->profissionaisModel = new ProfissionaisModel();
        // Initialize BaseRepository $model
        $this->model = $this->profissionaisModel;
        
        $this->enderecosModel = new EnderecosProfissionaisModel();
        $this->db = \Config\Database::connect();
    }


    /**
     * Busca um profissional pelo ID
     * Sobreescreve o metodo findById da classe BaseRepository
     * 
     * @param int $id
     * @return ?array
     */
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

    /**
     * Busca os IDs das relações de um profissional
     * 
     * @param int $professionalId
     * @param string $table
     * @param string $fkColumn
     * @return array
     */
    protected function getRelatedIds(int $professionalId, string $table, string $fkColumn): array
    {
        return $this->db->table($table)
            ->select($fkColumn)
            ->where('profissional_id', $professionalId)
            ->get()
            ->getResultArray();
    }
    
    /**
     * Busca os nomes das relações de um profissional
     * 
     * @param int $professionalId
     * @param string $pivotTable
     * @param string $relatedTable
     * @param string $fkPivot
     * @param string $nameCol
     * @return array
     */
    public function getRelatedNames(int $professionalId, string $pivotTable, string $relatedTable, string $fkPivot, string $nameCol = 'nome'): array
    {
         return $this->db->table($pivotTable)
            ->select("$relatedTable.$nameCol")
            ->join($relatedTable, "$relatedTable.id = $pivotTable.$fkPivot")
            ->where("$pivotTable.profissional_id", $professionalId)
            ->get()
            ->getResultArray();
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

    /**
     * Sincroniza as relações de um profissional
     * 
     * @param int $professionalId
     * @param string $pivotTable
     * @param string $fkColumn
     * @param array $ids
     * @return void
     */
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
