<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'tipo_processo_id',
        'interessado_id',
        'assunto',
        'descricao',
        'nivel_acesso',
        'status',
        'data_abertura',
        'data_fechamento',
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime',
    ];

    public function tipoProcesso()
    {
        return $this->belongsTo(TipoProcesso::class);
    }

    public function interessado()
    {
        return $this->belongsTo(User::class, 'interessado_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
