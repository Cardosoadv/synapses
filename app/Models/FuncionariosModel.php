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
        'user_id', 'cpf', 'registration_number', 'position', 'department',
        'status', 'photo',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'cpf' => 'required|valid_cpf|is_unique[employees.cpf,id,{id}]',
        'registration_number' => 'required|is_unique[employees.registration_number,id,{id}]',
    ];
    protected $validationMessages   = [
        'cpf' => [
            'required' => 'O CPF é obrigatório.',
            'valid_cpf' => 'O CPF informado não é válido.',
            'is_unique' => 'Este CPF já está cadastrado.'
        ],
        'registration_number' => [
             'required' => 'A matrícula é obrigatória.',
             'is_unique' => 'Esta matrícula já está em uso.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks provided by BaseModel -> AuditoriaTrait
    // We can also add specific ones here if needed
}
