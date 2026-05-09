@extends('layouts.app')

@section('title', 'Novo Tipo de Processo')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('tipos-processos.index') }}" style="color: var(--text-muted); text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 style="margin-top: 1rem; font-size: 1.5rem;">Novo Tipo de Processo</h1>
    </div>

    <div class="glass" style="padding: 2rem;">
        <form action="{{ route('tipos-processos.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Nome do Tipo *</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required placeholder="Ex: Administrativo">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Prefixo (Opcional)</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo') }}" placeholder="Ex: ADM">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao') }}" placeholder="Ex: 30">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva a finalidade deste tipo de processo...">{{ old('descricao') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
