<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\AuditoriaTrait;

class FuncionariosModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'user_id', 'registration_number', 'position', 'department',
        'status', 'is_lawyer', 'rateio_ativo', 'oab_numero', 'oab_uf', 'photo',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks provided by BaseModel -> AuditoriaTrait
    // We can also add specific ones here if needed
}
