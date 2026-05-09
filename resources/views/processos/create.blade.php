@extends('layouts.app')

@section('title', 'Novo Processo')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('processos.index') }}" style="color: var(--text-muted); text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 style="margin-top: 1rem; font-size: 1.5rem;">Novo Processo</h1>
        <p style="color: var(--text-muted);">A numeração será gerada automaticamente após o salvamento.</p>
    </div>

    <div class="glass" style="padding: 2rem;">
        <form action="{{ route('processos.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Tipo de Processo *</label>
                    <select name="tipo_processo_id" class="form-control" required>
                        <option value="">Selecione o tipo...</option>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_processo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Interessado (Opcional)</label>
                    <select name="interessado_id" class="form-control">
                        <option value="">Selecione um usuário...</option>
                        @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('interessado_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }} ({{ $usuario->cpf }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Assunto *</label>
                <input type="text" name="assunto" class="form-control" value="{{ old('assunto') }}" required placeholder="Título ou resumo do processo">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Nível de Acesso *</label>
                <div style="display: flex; gap: 2rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="nivel_acesso" value="publico" {{ old('nivel_acesso', 'publico') === 'publico' ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        Público
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="nivel_acesso" value="restrito" {{ old('nivel_acesso') === 'restrito' ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        Restrito
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="nivel_acesso" value="sigiloso" {{ old('nivel_acesso') === 'sigiloso' ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        Sigiloso
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Descrição Detalhada</label>
                <textarea name="descricao" class="form-control" rows="6" placeholder="Forneça detalhes adicionais sobre o processo...">{{ old('descricao') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gerar Processo</button>
            </div>
        </form>
    </div>
</div>
@endsection
