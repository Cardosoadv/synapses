@extends('layouts.app')

@section('title', 'Novo Tipo de Processo')

@section('content')
<div class="max-w-800 mx-auto">
    <div class="mb-2">
        <a href="{{ route('tipos-processos.index') }}" class="text-muted" style="text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <h1 class="mt-1 fs-1-5">Novo Tipo de Processo</h1>
    </div>

    <div class="glass p-2">
        <form action="{{ route('tipos-processos.store') }}" method="POST">
            @csrf
            
            <div class="mb-1-5">
                <label class="d-block mb-0-5 text-muted form-label-required">Nome do Tipo</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required placeholder="Ex: Administrativo">
            </div>

            <div class="d-grid-2 gap-1-5 mb-1-5">
                <div>
                    <label class="d-block mb-0-5 text-muted">Prefixo (Opcional)</label>
                    <input type="text" name="prefixo" class="form-control" value="{{ old('prefixo') }}" placeholder="Ex: ADM">
                </div>
                <div>
                    <label class="d-block mb-0-5 text-muted">Prazo de Conclusão (Dias)</label>
                    <input type="number" name="prazo_conclusao" class="form-control" value="{{ old('prazo_conclusao') }}" placeholder="Ex: 30">
                </div>
            </div>

            <div class="mb-1-5">
                <label class="d-block mb-0-5 text-muted">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva a finalidade deste tipo de processo...">{{ old('descricao') }}</textarea>
            </div>

            <div class="d-flex flex-end gap-1 mt-2">
                <a href="{{ route('tipos-processos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Tipo</button>
            </div>
        </form>
    </div>
</div>
@endsection
