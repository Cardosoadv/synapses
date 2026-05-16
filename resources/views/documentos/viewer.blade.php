<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador - Processo {{ $processo->numero }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { margin: 0; overflow: hidden; display: flex; height: 100vh; background-color: var(--bg); color: var(--text); }
        .viewer-sidebar { width: 350px; background: var(--bg-card); border-right: 1px solid var(--border); display: flex; flex-direction: column; }
        .viewer-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
        .viewer-content { flex: 1; display: flex; flex-direction: column; }
        .document-list { flex: 1; overflow-y: auto; padding: 1rem; }
        .document-item { padding: 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem; border: 1px solid transparent; cursor: pointer; text-decoration: none; display: block; color: var(--text); transition: all 0.2s; }
        .document-item:hover { background: rgba(255,255,255,0.05); }
        .document-item.active { background: rgba(255,255,255,0.1); border-color: var(--primary); }
        .document-item-title { font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem; }
        .document-item-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; justify-content: space-between; }
        .pdf-container { flex: 1; background: #333; }
        iframe { width: 100%; height: 100%; border: none; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1rem; font-size: 0.875rem; transition: color 0.2s; }
        .btn-back:hover { color: var(--text); }
    </style>
</head>
<body>
    <div class="viewer-sidebar">
        <div class="viewer-header">
            <a href="{{ route('processos.show', $processo->id) }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Voltar ao Processo
            </a>
            <h2 style="font-size: 1.25rem; margin: 0; color: var(--primary);">{{ $processo->numero }}</h2>
            <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $processo->assunto }}</div>
        </div>
        <div class="document-list">
            @foreach($documentos as $doc)
                <a href="{{ route('documentos.viewer', $doc->uuid) }}" class="document-item {{ $documentoAtual->id === $doc->id ? 'active' : '' }}">
                    <div class="document-item-title">{{ $doc->titulo }}</div>
                    <div class="document-item-meta">
                        <span><i class="bi bi-file-earmark-pdf"></i> {{ strtoupper($doc->tipo_documento) }}</span>
                        <span>{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div style="padding: 1rem; border-top: 1px solid var(--border); text-align: center; font-size: 0.75rem; color: var(--text-muted);">
            Exibindo {{ $documentos->count() }} documento(s)
        </div>
    </div>
    <div class="viewer-content">
        <div style="padding: 1rem; background: var(--bg-card); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 1.1rem; margin: 0;">{{ $documentoAtual->titulo }}</h1>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('documentos.download', $documentoAtual->uuid) }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                    <i class="bi bi-download"></i> Baixar
                </a>
            </div>
        </div>
        <div class="pdf-container">
            <iframe src="{{ route('documentos.view', $documentoAtual->uuid) }}#toolbar=0&navpanes=0&scrollbar=0" title="{{ $documentoAtual->titulo }}"></iframe>
        </div>
    </div>
</body>
</html>
