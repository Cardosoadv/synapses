<?php

namespace App\Http\Requests\Processo;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_processo_id' => 'required|exists:tipos_processos,id',
            'interessado_id' => 'nullable|exists:users,id',
            'assunto' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'nivel_acesso' => 'required|in:publico,restrito,sigiloso',
        ];
    }
}
