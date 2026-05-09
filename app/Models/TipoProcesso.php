<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoProcesso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos_processos';

    protected $fillable = [
        'nome',
        'descricao',
        'prefixo',
        'prazo_conclusao',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function processos()
    {
        return $this->hasMany(Processo::class);
    }
}
