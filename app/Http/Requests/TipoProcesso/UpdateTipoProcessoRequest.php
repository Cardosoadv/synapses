<?php

namespace App\Http\Requests\TipoProcesso;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTipoProcessoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('tipo_processo') ?: $this->route('id');

        return [
            'nome' => "required|string|unique:tipos_processos,nome,{$id}",
            'descricao' => 'nullable|string',
            'prefixo' => 'nullable|string|max:10',
            'prazo_conclusao' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }
}
