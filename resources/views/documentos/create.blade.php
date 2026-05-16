@extends('layouts.app')

@section('title', 'Incluir Documento')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('processos.show', $processo->id) }}" style="color: var(--text-muted); text-decoration: none;">
            <i class="bi bi-arrow-left"></i> Voltar para o processo
        </a>
        <h1 style="margin-top: 1rem; font-size: 1.5rem;">Incluir Documento</h1>
        <p style="color: var(--text-muted);">Processo: <strong>{{ $processo->numero }}</strong></p>
    </div>

    <div class="glass" style="padding: 2rem;">
        <form action="{{ route('documentos.store', $processo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Título do Documento *</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required placeholder="Ex: Relatório Técnico, Ofício 123/2026, etc.">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Arquivo PDF *</label>
                <div style="border: 2px dashed var(--border); padding: 2rem; border-radius: 0.75rem; text-align: center; position: relative;">
                    <i class="bi bi-file-earmark-pdf" style="font-size: 2.5rem; color: var(--primary); display: block; margin-bottom: 1rem;"></i>
                    <p style="margin-bottom: 1rem; font-size: 0.875rem;">Arraste o arquivo aqui ou clique para selecionar</p>
                    <input type="file" name="arquivo" accept="application/pdf" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    <div id="file-name" style="font-weight: 600; color: var(--success);">Nenhum arquivo selecionado</div>
                </div>
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Tamanho máximo: 10MB. Apenas arquivos .pdf são aceitos.</small>
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

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-cloud-upload"></i> Incluir Documento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Nenhum arquivo selecionado';
        document.getElementById('file-name').textContent = fileName;
    });
</script>
@endsection
