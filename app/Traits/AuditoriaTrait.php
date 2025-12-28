<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\AuditoriaModel;

/**
 * Trait para reutilizar lógica de auditoria em Models.
 */
trait AuditoriaTrait
{
    protected ?AuditoriaModel $auditoriaModelInstance = null;
    protected ?string $ipAddress = null;

    /**
     * Inicializa dependências de auditoria se necessário.
     */
    protected function initAuditoria(): void
    {
        if ($this->auditoriaModelInstance === null) {
            $this->auditoriaModelInstance = new AuditoriaModel();
        }
        if ($this->ipAddress === null) {
            $this->ipAddress = service('request')->getIPAddress();
        }
    }

    /**
     * Callback para registrar auditoria antes da criação (apenas getID no after).
     * Nota: Auditoria de create geralmente é feita no afterInsert para ter o ID.
     */
    protected function auditoriaNovo(array $data): array
    {
        $this->initAuditoria();

        $id = $data['id'] ?? $this->getInsertID();

        $this->auditoriaModelInstance->insert([
            'user_id'     => user_id(),
            'table_name'  => $this->table,
            'action_type' => 'CREATE',
            'dados_novos' => json_encode($data['data'] ?? $data),
            'ip_address'  => $this->ipAddress,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return $data;
    }

    /**
     * Callback para registrar auditoria antes da atualização.
     */
    protected function auditoriaAtualizar(array $data): array
    {
        $this->initAuditoria();

        // Verifica se há ID para buscar dados antigos
        if (!isset($data['id'])) {
            // Em updates em lote ou sem ID explícito no array de dados do evento, 
            // pode ser difícil auditar o "antes". 
            // O CodeIgniter geralmente passa 'id' (array ou int) nos dados do evento beforeUpdate.
            return $data;
        }

        $ids = is_array($data['id']) ? $data['id'] : [$data['id']];

        foreach ($ids as $id) {
            $dadosAntigos = $this->find($id);

            $this->auditoriaModelInstance->insert([
                'user_id'       => user_id(),
                'table_name'    => $this->table,
                'action_type'   => 'UPDATE',
                'dados_antigos' => json_encode($dadosAntigos),
                'dados_novos'   => json_encode($data['data']),
                'ip_address'    => $this->ipAddress,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        return $data;
    }

    /**
     * Callback para registrar auditoria antes da exclusão.
     */
    protected function auditoriaDeletar(array $data): array
    {
        $this->initAuditoria();

        if (isset($data['id'])) {
            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];

            foreach ($ids as $id) {
                $dadosAntigos = $this->find($id);

                $this->auditoriaModelInstance->insert([
                    'user_id'       => user_id(),
                    'table_name'    => $this->table,
                    'action_type'   => 'DELETE',
                    'dados_antigos' => json_encode($dadosAntigos),
                    'ip_address'    => $this->ipAddress,
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $data;
    }
}
