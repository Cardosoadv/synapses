@extends('layouts.app')

@section('title', 'Processos')

@section('content')
<div class="glass" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0; font-size: 1.5rem;">Processos</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Registro e acompanhamento de processos administrativos.</p>
        </div>
        <a href="{{ route('processos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Processo
        </a>
    </div>

    <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: flex-end;">
        <form action="{{ route('processos.index') }}" method="GET" style="display: flex; gap: 1rem; flex-grow: 1;">
            <div style="flex-grow: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.75rem;">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Número ou assunto..." value="{{ request('search') }}">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.75rem;">Tipo</label>
                <select name="tipo_processo_id" class="form-control" style="width: 200px;">
                    <option value="">Todos os Tipos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ request('tipo_processo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.75rem;">Status</label>
                <select name="status" class="form-control" style="width: 150px;">
                    <option value="">Todos</option>
                    <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="arquivado" {{ request('status') == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline" style="height: 45px;">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div style="overflow-x: auto;">
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
                        <div style="font-weight: 700; color: var(--primary);">{{ $processo->numero }}</div>
                        <div style="font-size: 0.875rem;">{{ $processo->assunto }}</div>
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
                        <span style="font-size: 0.75rem; text-transform: uppercase;">
                            <i class="bi {{ $processo->nivel_acesso === 'publico' ? 'bi-unlock' : 'bi-lock' }}"></i>
                            {{ $processo->nivel_acesso }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem;" title="Ver Detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" onsubmit="return confirm('Excluir este processo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; color: var(--danger);" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        Nenhum processo encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $processos->links() }}
    </div>
</div>
@endsection
