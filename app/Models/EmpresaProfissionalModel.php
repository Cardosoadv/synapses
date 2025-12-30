<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\AuditoriaTrait;

class EmpresaProfissionalModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'empresa_profissional';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'empresa_id',
        'profissional_id',
        'motivo_id',
        'comentario',
        'data_inicio',
        'data_fim',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
