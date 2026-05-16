@extends('layouts.app')

@section('title', 'Detalhes do Processo')

@section('content')
<div class="container-wide">
    <div class="detail-header">
        <div>
            <a href="{{ route('processos.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Voltar para a lista
            </a>
            <h1>{{ $processo->numero }}</h1>
            <p class="detail-subtitle">{{ $processo->assunto }}</p>
        </div>
        <div class="detail-actions">
            <form action="{{ route('processos.update-status', $processo->id) }}" method="POST" id="status-form">
                @csrf
                @method('PATCH')
                <select name="status" class="form-control status-select" style="width: auto; background: var(--bg-card);" aria-label="Alterar status do processo">
                    <option value="aberto" {{ $processo->status === 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_analise" {{ $processo->status === 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="concluido" {{ $processo->status === 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="arquivado" {{ $processo->status === 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </form>
            <a href="{{ route('documentos.create', $processo->id) }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-plus"></i> Adicionar Documento
            </a>
        </div>
    </div>

    <div class="grid-main">
        <div class="glass section-glass">
            <h3 class="card-title">Informações Gerais</h3>
            
            <div class="info-block">
                <label class="info-label">Descrição</label>
                <div class="info-value">{{ $processo->descricao ?? 'Sem descrição detalhada.' }}</div>
            </div>

            <div class="grid-2nd">
                <div>
                    <label class="info-label">Data de Abertura</label>
                    <div>{{ $processo->data_abertura->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <label class="info-label">Data de Fechamento</label>
                    <div>{{ $processo->data_fechamento ? $processo->data_fechamento->format('d/m/Y H:i') : '-' }}</div>
                </div>
            </div>

            <h3 class="card-title" style="margin-top: 2rem;">Documentos</h3>
            <div class="documentos-list">
                @forelse($documentos as $documento)
                    <div class="documento-item glass" style="padding: 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $documento->titulo }}</strong>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                Nível de Acesso: {{ ucfirst($documento->nivel_acesso) }} | Data: {{ $documento->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="documento-actions">
                            <a href="{{ route('documentos.view', $documento->uuid) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('documentos.download', $documento->uuid) }}" class="btn btn-sm btn-outline-secondary" title="Baixar">
                                <i class="bi bi-download"></i>
                            </a>
                            <form action="{{ route('documentos.destroy', $documento->uuid) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este documento?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Nenhum documento anexado a este processo.</p>
                @endforelse
            </div>
        </div>

        <div class="sidebar-stack">
            <div class="glass" style="padding: 1.5rem;">
                <h3 class="card-title-small">Classificação</h3>
                
                <div style="margin-bottom: 1rem;">
                    <label class="info-label-small">Tipo</label>
                    <div class="info-value-bold">{{ $processo->tipoProcesso->nome }}</div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="info-label-small">Nível de Acesso</label>
                    <div class="text-capitalize">
                        <i class="bi {{ $processo->nivel_acesso === 'publico' ? 'bi-unlock' : 'bi-lock' }}"></i>
                        {{ $processo->nivel_acesso }}
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="info-label-small">Interessado</label>
                    <div>{{ $processo->interessado->name ?? 'Não informado' }}</div>
                </div>
            </div>

            <div class="glass" style="padding: 1.5rem;">
                <h3 class="card-title-small">Histórico de Movimentações</h3>
                <div class="timeline">
                    @forelse($processo->movimentacoes->sortByDesc('created_at') as $movimentacao)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="info-label-small">{{ $movimentacao->created_at->format('d/m/Y H:i') }}</div>
                            <div class="td-main">
                                <strong>{{ ucfirst(str_replace('_', ' ', $movimentacao->status_novo)) }}</strong>
                                <p style="font-size: 0.85rem; margin-top: 0.25rem;">{{ $movimentacao->observacao }}</p>
                                <small class="text-muted">Por: {{ $movimentacao->user->name ?? 'Sistema' }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="info-label-small">{{ $processo->data_abertura->format('d/m/Y H:i') }}</div>
                            <div class="td-main">Processo Criado</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
