@extends('layouts.app')

@section('title', 'Editar Tipo de Processo')

@section('content')
<div class="max-w-800 mx-auto">
    <div class="mb-2">
        <a href="{{ route('tipos-processos.index') }}" class="text-muted" style="text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="mt-1 fs-1-5">Editar Tipo de Processo</h1>
    </div>

    <div class="glass p-2">
        <form action="{{ route('tipos-processos.update', $tipo->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-1-5">
                <label class="d-block mb-0-5 text-muted form-label-required">Nome do Tipo</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $tipo->nome) }}" required>
            </div>

            <div class="d-grid-2 gap-1-5 mb-1-5">
                <div>
                    <label class="d-block mb-0-5 text-muted">Prefixo</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo', $tipo->prefixo) }}">
                </div>
                <div>
                    <label class="d-block mb-0-5 text-muted">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao', $tipo->prazo_conclusao) }}">
                </div>
            </div>

            <div class="mb-1-5">
                <label class="d-block mb-0-5 text-muted">Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $tipo->is_active ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ !$tipo->is_active ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="mb-1-5">
                <label class="d-block mb-0-5 text-muted">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $tipo->descricao) }}</textarea>
            </div>

            <div class="d-flex flex-end gap-1 mt-2">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
