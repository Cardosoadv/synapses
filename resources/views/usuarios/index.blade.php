@extends('layouts.app')

@section('title', 'Gestão de Usuários')

@section('content')
<div class="page-header">
    <div>
        <h1>Usuários</h1>
        <p>Gerencie os acessos e perfis do sistema</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Usuário
    </a>
</div>

<div class="glass section-glass-no-padding">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Status</th>
                <th>Último Login</th>
                <th class="text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
            <tr>
                <td>
                    <div class="td-main">{{ $user->name }}</div>
                    <div class="td-sub">{{ $user->cpf ?? 'Sem CPF' }}</div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-{{ $user->role }}">{{ strtoupper($user->role) }}</span>
                </td>
                <td>
                    <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="toggle-status-btn" aria-label="Alterar status de {{ $user->name }}" title="Clique para alternar o status">
                            <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </button>
                    </form>
                </td>
                <td class="td-sub fs-0-875">
                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca logou' }}
                </td>
                <td class="text-right">
                    <div class="td-actions td-actions-end">
                        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-outline btn-action" title="Editar" aria-label="Editar usuário {{ $user->name }}">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" data-confirm="Tem certeza que deseja remover este usuário?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-action btn-delete" title="Excluir" aria-label="Excluir usuário {{ $user->name }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-border">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
