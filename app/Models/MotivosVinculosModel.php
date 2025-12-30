<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\AuditoriaTrait;

class MotivosVinculosModel extends BaseModel
{
    use AuditoriaTrait;

    protected $table            = 'motivos_vinculos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
