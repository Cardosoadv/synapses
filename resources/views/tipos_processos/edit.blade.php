@extends('layouts.app')

@section('title', 'Editar Tipo de Processo')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('tipos-processos.index') }}" style="color: var(--text-muted); text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 style="margin-top: 1rem; font-size: 1.5rem;">Editar Tipo de Processo</h1>
    </div>

    <div class="glass" style="padding: 2rem;">
        <form action="{{ route('tipos-processos.update', $tipo->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Nome do Tipo *</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $tipo->nome) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Prefixo</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo', $tipo->prefixo) }}">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao', $tipo->prazo_conclusao) }}">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $tipo->is_active ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ !$tipo->is_active ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $tipo->descricao) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
