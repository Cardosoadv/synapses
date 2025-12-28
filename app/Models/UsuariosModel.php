<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use App\Traits\AuditoriaTrait;

class UsuariosModel extends ShieldUserModel
{
    use AuditoriaTrait;

    protected $allowedFields = [
        'username', 'status', 'status_message', 'active', 'last_active', 'deleted_at',
        'is_lawyer', 'rateio_ativo', 'oab_numero', 'oab_uf', 'auth_image'
    ];
    
    // Callbacks provided by Shield
    // protected $beforeInsert = ['hashPassword'];
    // protected $afterInsert  = ['saveEmailIdentity', 'addToDefaultGroup'];
    // ...
    
    // We override initialize to append our auditoria callbacks ensuring we don't overwrite Shield's
    protected function initialize(): void
    {
        parent::initialize();
        
        $this->afterInsert[] = 'auditoriaNovo';
        $this->beforeUpdate[] = 'auditoriaAtualizar';
        $this->beforeDelete[] = 'auditoriaDeletar';
    }
}
