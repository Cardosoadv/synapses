@extends('layouts.app')

@section('title', 'Gestão de Usuários')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="margin: 0;">Usuários</h1>
        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Gerencie os acessos e perfis do sistema</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo Usuário
    </a>
</div>

<div class="glass" style="overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Status</th>
                <th>Último Login</th>
                <th style="text-align: right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
            <tr>
                <td>
                    <div style="font-weight: 500;">{{ $user->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->cpf ?? 'Sem CPF' }}</div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-{{ $user->role }}">{{ strtoupper($user->role) }}</span>
                </td>
                <td>
                    <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                            <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </button>
                    </form>
                </td>
                <td style="color: var(--text-muted); font-size: 0.875rem;">
                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca logou' }}
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-outline" style="padding: 0.5rem;">
                            <i class="bi bi-pencil" title="Editar"></i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este usuário?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 0.5rem; color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                                <i class="bi bi-trash" title="Excluir"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="padding: 1rem; border-top: 1px solid var(--border);">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
