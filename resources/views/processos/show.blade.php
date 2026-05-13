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
                <select name="status" class="form-control status-select" aria-label="Alterar status do processo">
                    <option value="aberto" {{ $processo->status === 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_analise" {{ $processo->status === 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="concluido" {{ $processo->status === 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="arquivado" {{ $processo->status === 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </form>
            <button class="btn btn-primary" title="Adicionar novo documento" aria-label="Adicionar novo documento ao processo">
                <i class="bi bi-file-earmark-plus"></i> Adicionar Documento
            </button>
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
        </div>

        <div class="sidebar-stack">
            <div class="glass p-1-5">
                <h3 class="card-title-small">Classificação</h3>
                
                <div class="mb-1">
                    <label class="info-label-small">Tipo</label>
                    <div class="info-value-bold">{{ $processo->tipoProcesso->nome }}</div>
                </div>

                <div class="mb-1">
                    <label class="info-label-small">Nível de Acesso</label>
                    <div class="text-capitalize">
                        <i class="bi {{ $processo->nivel_acesso === 'publico' ? 'bi-unlock' : 'bi-lock' }}"></i>
                        {{ $processo->nivel_acesso }}
                    </div>
                </div>

                <div class="mb-1">
                    <label class="info-label-small">Interessado</label>
                    <div>{{ $processo->interessado->name ?? 'Não informado' }}</div>
                </div>
            </div>

            <div class="glass p-1-5">
                <h3 class="card-title-small">Histórico de Movimentações</h3>
                <div class="timeline">
                    @forelse($processo->movimentacoes->sortByDesc('created_at') as $movimentacao)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="info-label-small">{{ $movimentacao->created_at->format('d/m/Y H:i') }}</div>
                            <div class="td-main">
                                <strong>{{ ucfirst(str_replace('_', ' ', $movimentacao->status_novo)) }}</strong>
                                <p class="history-obs">{{ $movimentacao->observacao }}</p>
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
