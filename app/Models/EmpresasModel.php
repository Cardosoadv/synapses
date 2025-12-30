<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\AuditoriaTrait;

class EmpresasModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'empresas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cnpj',
        'razao_social',
        'nome_fantasia',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks are handled by BaseModel + AuditoriaTrait
}
