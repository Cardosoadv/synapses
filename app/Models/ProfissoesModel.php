<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\AuditoriaTrait;

class ProfissoesModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'profissoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'fundamento_legal'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
