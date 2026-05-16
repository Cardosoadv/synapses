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
        <div>
            <div class="glass section-glass" style="margin-bottom: 2rem;">
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

            <div class="glass section-glass">
                <h3 class="card-title">Documentos</h3>
                
                @if($documentos->isEmpty())
                    <div class="table-empty">
                        <i class="bi bi-file-earmark" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                        Nenhum documento incluído neste processo.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Usuário</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentos as $doc)
                                    <tr>
                                        <td class="td-numero" style="font-family: monospace;">{{ $doc->numero_documento }}</td>
                                        <td>{{ $doc->titulo }}</td>
                                        <td>
                                            <span class="td-acesso" style="padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.25rem;">
                                                {{ $doc->tipo_documento }}
                                            </span>
                                        </td>
                                        <td class="td-sub">{{ $doc->user->name }}</td>
                                        <td>
                                            <div class="td-actions td-actions-end">
                                                <a href="{{ route('documentos.viewer', $doc->uuid) }}" class="btn btn-icon" title="Visualizar/Folhear" aria-label="Visualizar ou folhear documento">
                                                    <i class="bi bi-book"></i>
                                                </a>
                                                <a href="{{ route('documentos.view', $doc->uuid) }}" target="_blank" class="btn btn-icon" title="Visualizar PDF" aria-label="Abrir PDF em nova aba">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('documentos.download', $doc->uuid) }}" class="btn btn-icon" title="Download" aria-label="Baixar documento">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <form action="{{ route('documentos.destroy', $doc->uuid) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este documento?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-delete" title="Excluir" aria-label="Excluir documento">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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
