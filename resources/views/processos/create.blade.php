@extends('layouts.app')

@section('title', 'Novo Processo')

@section('content')
<div class="container-narrow">
    <div class="info-block">
        <a href="{{ route('processos.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="form-label-md" style="margin-top: 1rem; font-size: 1.5rem;">Novo Processo</h1>
        <p class="info-label">A numeração será gerada automaticamente após o salvamento.</p>
    </div>

    <div class="glass section-glass">
        <form action="{{ route('processos.store') }}" method="POST">
            @csrf
            
            <div class="grid-2nd form-group">
                <div>
                    <label class="form-label form-label-required">Tipo de Processo</label>
                    <select name="tipo_processo_id" class="form-control" required>
                        <option value="">Selecione o tipo...</option>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_processo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Interessado (Opcional)</label>
                    <select name="interessado_id" class="form-control">
                        <option value="">Selecione um usuário...</option>
                        @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('interessado_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }} ({{ $usuario->cpf }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label form-label-required">Assunto</label>
                <input type="text" name="assunto" class="form-control" value="{{ old('assunto') }}" required placeholder="Título ou resumo do processo">
            </div>

            <div class="form-group">
                <label class="form-label form-label-required">Nível de Acesso</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="nivel_acesso" value="publico" {{ old('nivel_acesso', 'publico') === 'publico' ? 'checked' : '' }}>
                        Público
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="nivel_acesso" value="restrito" {{ old('nivel_acesso') === 'restrito' ? 'checked' : '' }}>
                        Restrito
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="nivel_acesso" value="sigiloso" {{ old('nivel_acesso') === 'sigiloso' ? 'checked' : '' }}>
                        Sigiloso
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Descrição Detalhada</label>
                <textarea name="descricao" class="form-control" rows="6" placeholder="Forneça detalhes adicionais sobre o processo...">{{ old('descricao') }}</textarea>
            </div>

            <div class="td-actions td-actions-end" style="margin-top: 2rem;">
                <a href="{{ route('processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gerar Processo</button>
            </div>
        </form>
    </div>
</div>
@endsection
