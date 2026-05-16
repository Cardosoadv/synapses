<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Documento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'processo_id',
        'user_id',
        'titulo',
        'tipo_documento',
        'arquivo_path',
        'conteudo',
        'nivel_acesso',
        'status',
        'numero_documento',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($documento) {
            if (empty($documento->uuid)) {
                $documento->uuid = (string) Str::uuid();
            }
        });
    }
}
