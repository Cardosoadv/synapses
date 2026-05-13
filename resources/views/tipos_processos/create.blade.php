@extends('layouts.app')

@section('title', 'Novo Tipo de Processo')

@section('content')
<div class="container-medium">
    <div class="mb-2">
        <a href="{{ route('tipos-processos.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="mt-1 fs-md">Novo Tipo de Processo</h1>
    </div>

    <div class="glass p-2">
        <form action="{{ route('tipos-processos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label form-label-required">Nome do Tipo</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required placeholder="Ex: Administrativo">
            </div>

            <div class="grid-2nd form-group">
                <div>
                    <label class="form-label">Prefixo (Opcional)</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo') }}" placeholder="Ex: ADM">
                </div>
                <div>
                    <label class="form-label">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao') }}" placeholder="Ex: 30">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva a finalidade deste tipo de processo...">{{ old('descricao') }}</textarea>
            </div>

            <div class="flex-end gap-1 mt-2">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
