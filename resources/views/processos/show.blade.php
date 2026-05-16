@extends('layouts.app')

@section('title', 'Detalhes do Processo')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('processos.index') }}" style="color: var(--text-muted); text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Voltar para a lista
            </a>
            <h1 style="margin-top: 1rem; font-size: 2rem; color: var(--primary);">{{ $processo->numero }}</h1>
            <p style="font-size: 1.25rem;">{{ $processo->assunto }}</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <form action="{{ route('processos.update-status', $processo->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" class="form-control" onchange="this.form.submit()" style="width: auto; background: var(--bg-card);">
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

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <div class="glass" style="padding: 2rem; margin-bottom: 2rem;">
                <h3 style="margin-top: 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem;">Informações Gerais</h3>
                
                <div style="margin-bottom: 2rem;">
                    <label style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Descrição</label>
                    <div style="line-height: 1.6;">{{ $processo->descricao ?? 'Sem descrição detalhada.' }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <label style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Data de Abertura</label>
                        <div>{{ $processo->data_abertura->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Data de Fechamento</label>
                        <div>{{ $processo->data_fechamento ? $processo->data_fechamento->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="glass" style="padding: 2rem;">
                <h3 style="margin-top: 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem;">Documentos</h3>
                
                @if($documentos->isEmpty())
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <i class="bi bi-file-earmark" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                        Nenhum documento incluído neste processo.
                    </div>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $doc)
                                <tr>
                                    <td style="font-family: monospace;">{{ $doc->numero_documento }}</td>
                                    <td>{{ $doc->titulo }}</td>
                                    <td>
                                        <span style="padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.25rem; font-size: 0.75rem; text-transform: uppercase;">
                                            {{ $doc->tipo_documento }}
                                        </span>
                                    </td>
                                    <td style="font-size: 0.875rem;">{{ $doc->user->name }}</td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ route('documentos.viewer', $doc->uuid) }}" class="btn btn-icon" title="Visualizar/Folhear">
                                                <i class="bi bi-book"></i>
                                            </a>
                                            <a href="{{ route('documentos.view', $doc->uuid) }}" target="_blank" class="btn btn-icon" title="Visualizar PDF">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('documentos.download', $doc->uuid) }}" class="btn btn-icon" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <form action="{{ route('documentos.destroy', $doc->uuid) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este documento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-danger" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <div class="glass" style="padding: 1.5rem;">
                <h3 style="margin-top: 0; font-size: 1.1rem; margin-bottom: 1.25rem;">Classificação</h3>
                
                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); font-size: 0.75rem; display: block;">Tipo</label>
                    <div style="font-weight: 600;">{{ $processo->tipoProcesso->nome }}</div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); font-size: 0.75rem; display: block;">Nível de Acesso</label>
                    <div style="text-transform: capitalize;">
                        <i class="bi {{ $processo->nivel_acesso === 'publico' ? 'bi-unlock' : 'bi-lock' }}"></i>
                        {{ $processo->nivel_acesso }}
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); font-size: 0.75rem; display: block;">Interessado</label>
                    <div>{{ $processo->interessado->name ?? 'Não informado' }}</div>
                </div>
            </div>

            <div class="glass" style="padding: 1.5rem;">
                <h3 style="margin-top: 0; font-size: 1.1rem; margin-bottom: 1.25rem;">Timeline</h3>
                <div style="border-left: 2px solid var(--border); padding-left: 1.5rem; position: relative;">
                    <div style="margin-bottom: 1.5rem; position: relative;">
                        <div style="position: absolute; left: -1.95rem; top: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--success);"></div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $processo->data_abertura->format('d/m/Y H:i') }}</div>
                        <div style="font-weight: 500;">Processo Criado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
