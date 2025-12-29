<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\AuditoriaTrait;

class ProfissionaisModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'professionals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'name',
        'cpf',
        'registration_number',
        'sei_process',
        'status',
        'photo',
        'fingerprint',
        'signature',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks provided by BaseModel, but we ensure AuditoriaTrait methods are hooked
    // BaseModel already defines:
    // protected $beforeInsert   = [];
    // protected $afterInsert    = ['auditoriaNovo'];
    // protected $beforeUpdate   = ['auditoriaAtualizar'];
    // protected $beforeDelete   = ['auditoriaDeletar'];
    
    // So usually we don't need to redeclare unless we have extra callbacks.
}
