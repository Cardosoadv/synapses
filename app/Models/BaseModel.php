<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = ['auditoriaNovo'];
    protected $beforeUpdate   = ['auditoriaAtualizar'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['auditoriaDeletar'];
    protected $afterDelete    = [];

}
