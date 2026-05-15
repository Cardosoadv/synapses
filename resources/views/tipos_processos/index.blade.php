@extends('layouts.app')

@section('title', 'Tipos de Processos')

@section('content')
<div class="glass section-glass">
    <div class="page-header">
        <div>
            <h1>Tipos de Processos</h1>
            <p>Gerencie as categorias de processos do sistema.</p>
        </div>
        <a href="{{ route('tipos-processos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Tipo
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Prefixo</th>
                    <th scope="col">Prazo (Dias)</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                <tr>
                    <td>
                        <div class="td-main">{{ $tipo->nome }}</div>
                        <div class="td-sub">{{ Str::limit($tipo->descricao, 50) }}</div>
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
                        <div class="td-actions">
                            <a href="{{ route('tipos-processos.edit', $tipo->id) }}" class="btn btn-outline btn-action" title="Editar" aria-label="Editar tipo de processo {{ $tipo->nome }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tipos-processos.destroy', $tipo->id) }}" method="POST" data-confirm="Tem certeza que deseja excluir este tipo?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-action btn-delete" title="Excluir" aria-label="Excluir tipo de processo {{ $tipo->nome }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="table-empty">
                        Nenhum tipo de processo encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $tipos->links() }}
    </div>
</div>
@endsection
