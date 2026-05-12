<?php

namespace App\Http\Requests\TipoProcesso;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoProcessoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|unique:tipos_processos,nome',
            'descricao' => 'nullable|string',
            'prefixo' => 'nullable|string|max:10',
            'prazo_conclusao' => 'nullable|integer|min:1',
        ];
    }
}
