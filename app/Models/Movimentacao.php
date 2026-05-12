<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'processo_id',
        'user_id',
        'status_anterior',
        'status_novo',
        'observacao',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
