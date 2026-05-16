@extends('layouts.app')

@section('title', 'Editar Tipo de Processo')

@section('content')
<div class="container-800">
    <div class="mb-2">
        <a href="{{ route('tipos-processos.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="mt-1 fs-1-5">Editar Tipo de Processo</h1>
    </div>

    <div class="glass p-2">
        <form action="{{ route('tipos-processos.update', $tipo->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-1-5">
                <label class="form-label-required form-label">Nome do Tipo</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $tipo->nome) }}" required>
            </div>

            <div class="grid-2nd mb-1-5">
                <div>
                    <label class="form-label">Prefixo</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo', $tipo->prefixo) }}">
                </div>
                <div>
                    <label class="form-label">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao', $tipo->prazo_conclusao) }}">
                </div>
            </div>

            <div class="mb-1-5">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $tipo->is_active ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ !$tipo->is_active ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="mb-1-5">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $tipo->descricao) }}</textarea>
            </div>

            <div class="flex-end-gap-1 mt-2">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
