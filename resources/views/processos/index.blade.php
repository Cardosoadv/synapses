@extends('layouts.app')

@section('title', 'Processos')

@section('content')
<div class="glass section-glass">
    <div class="page-header">
        <div>
            <h1>Processos</h1>
            <p>Registro e acompanhamento de processos administrativos.</p>
        </div>
        <a href="{{ route('processos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Processo
        </a>
    </div>

    <div class="filters-bar">
        <form action="{{ route('processos.index') }}" method="GET">
            <div class="filter-group">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Número ou assunto..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Tipo</label>
                <select name="tipo_processo_id" class="form-control w-200">
                    <option value="">Todos os Tipos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ request('tipo_processo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control w-150">
                    <option value="">Todos</option>
                    <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="arquivado" {{ request('status') == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline h-45" title="Filtrar processos" aria-label="Filtrar processos">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Número / Assunto</th>
                    <th>Tipo</th>
                    <th>Interessado</th>
                    <th>Status</th>
                    <th>Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processos as $processo)
                <tr>
                    <td>
                        <div class="td-numero">{{ $processo->numero }}</div>
                        <div class="td-assunto">{{ $processo->assunto }}</div>
                    </td>
                    <td>
                        <span class="badge badge-manager">{{ $processo->tipoProcesso->nome }}</span>
                    </td>
                    <td>{{ $processo->interessado->name ?? 'Não informado' }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'aberto' => 'badge-active',
                                'em_analise' => 'badge-manager',
                                'concluido' => 'badge-active',
                                'arquivado' => 'badge-user'
                            ][$processo->status] ?? 'badge-user';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $processo->status)) }}</span>
                    </td>
                    <td>
                        <span class="td-acesso">
                            <i class="bi {{ $processo->nivel_acesso === 'publico' ? 'bi-unlock' : 'bi-lock' }}"></i>
                            {{ $processo->nivel_acesso }}
                        </span>
                    </td>
                    <td>
                        <div class="td-actions">
                            <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-outline btn-action" title="Ver Detalhes" aria-label="Ver detalhes do processo {{ $processo->numero }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" data-confirm="Excluir este processo?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-action btn-delete" title="Excluir" aria-label="Excluir processo {{ $processo->numero }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="table-empty">
                        Nenhum processo encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $processos->links() }}
    </div>
</div>
@endsection
