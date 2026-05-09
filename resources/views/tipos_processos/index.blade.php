@extends('layouts.app')

@section('title', 'Tipos de Processos')

@section('content')
<div class="glass" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0; font-size: 1.5rem;">Tipos de Processos</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Gerencie as categorias de processos do sistema.</p>
        </div>
        <a href="{{ route('tipos-processos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Tipo
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Prefixo</th>
                    <th>Prazo (Dias)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $tipo->nome }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($tipo->descricao, 50) }}</div>
                    </td>
                    <td><span class="badge badge-manager">{{ $tipo->prefixo ?? '-' }}</span></td>
                    <td>{{ $tipo->prazo_conclusao ?? 'N/A' }}</td>
                    <td>
                        @if($tipo->is_active)
                        <span class="badge badge-active">Ativo</span>
                        @else
                        <span class="badge badge-inactive">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('tipos-processos.edit', $tipo->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem;" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tipos-processos.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este tipo?')">
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
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        Nenhum tipo de processo encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $tipos->links() }}
    </div>
</div>
@endsection
