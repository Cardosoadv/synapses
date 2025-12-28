<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para a tabela de auditoria geral do sistema.
 *
 * Este modelo gerencia as operações de banco de dados para a tabela 'auditoria',
 * que registra logs de alterações em diversas tabelas do sistema.
 */
class AuditoriaModel extends Model
{
    /**
     * Nome da tabela no banco de dados.
     *
     * @var string
     */
    protected $table            = 'auditoria';

    /**
     * Chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey       = 'id';

    /**
     * Indica se a chave primária é auto-incrementável.
     *
     * @var bool
     */
    protected $useAutoIncrement = true;

    /**
     * Tipo de retorno dos resultados (array ou object).
     *
     * @var string
     */
    protected $returnType       = 'array';

    /**
     * Indica se deve usar soft deletes (exclusão lógica).
     *
     * @var bool
     */
    protected $useSoftDeletes   = false;

    /**
     * Indica se os campos devem ser protegidos contra inserção em massa.
     *
     * @var bool
     */
    protected $protectFields    = true;

    /**
     * Campos permitidos para inserção e atualização.
     *
     * @var array
     */
    protected $allowedFields    = [
        'user_id', 
        'table_name', 
        'action_type', 
        'dados_antigos', 
        'dados_novos', 
        'ip_address', 
    ];

    /**
     * Permite inserções vazias.
     *
     * @var bool
     */
    protected bool $allowEmptyInserts = false;

    /**
     * Atualiza apenas os campos alterados.
     *
     * @var bool
     */
    protected bool $updateOnlyChanged = true;

    /**
     * Definições de casting para os atributos do modelo.
     *
     * @var array
     */
    protected array $casts = [];

    /**
     * Handlers personalizados para casting.
     *
     * @var array
     */
    protected array $castHandlers = [];

    // Dates

    /**
     * Indica se deve usar timestamps (created_at, updated_at).
     *
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * Formato de data utilizado no banco de dados.
     *
     * @var string
     */
    protected $dateFormat    = 'datetime';

    /**
     * Nome do campo de data de criação.
     *
     * @var string
     */
    protected $createdField  = 'created_at';

    /**
     * Nome do campo de data de atualização.
     *
     * @var string
     */
    protected $updatedField  = 'updated_at';

    /**
     * Nome do campo de data de exclusão.
     *
     * @var string
     */
    protected $deletedField  = 'deleted_at';

    // Validation

    /**
     * Regras de validação.
     *
     * @var array
     */
    protected $validationRules      = [];

    /**
     * Mensagens de validação personalizadas.
     *
     * @var array
     */
    protected $validationMessages   = [];

    /**
     * Indica se deve pular a validação.
     *
     * @var bool
     */
    protected $skipValidation       = false;

    /**
     * Indica se as regras de validação devem ser limpas após a execução.
     *
     * @var bool
     */
    protected $cleanValidationRules = true;

    // Callbacks

    /**
     * Permite o uso de callbacks de eventos do modelo.
     *
     * @var bool
     */
    protected $allowCallbacks = true;

    /**
     * Callbacks executados antes da inserção.
     *
     * @var array
     */
    protected $beforeInsert   = [];

    /**
     * Callbacks executados após a inserção.
     *
     * @var array
     */
    protected $afterInsert    = [];

    /**
     * Callbacks executados antes da atualização.
     *
     * @var array
     */
    protected $beforeUpdate   = [];

    /**
     * Callbacks executados após a atualização.
     *
     * @var array
     */
    protected $afterUpdate    = [];

    /**
     * Callbacks executados antes de buscar registros.
     *
     * @var array
     */
    protected $beforeFind     = [];

    /**
     * Callbacks executados após buscar registros.
     *
     * @var array
     */
    protected $afterFind      = [];

    /**
     * Callbacks executados antes da exclusão.
     *
     * @var array
     */
    protected $beforeDelete   = [];

    /**
     * Callbacks executados após a exclusão.
     *
     * @var array
     */
    protected $afterDelete    = [];
}
