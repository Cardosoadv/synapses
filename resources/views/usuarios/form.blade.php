@extends('layouts.app')

@section('title', isset($usuario) ? 'Editar Usuário' : 'Novo Usuário')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('usuarios.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">
        <i class="bi bi-arrow-left"></i> Voltar para a listagem
    </a>
    <h1 style="margin: 0.5rem 0 0 0;">{{ isset($usuario) ? 'Editar Usuário' : 'Criar Novo Usuário' }}</h1>
</div>

<form action="{{ isset($usuario) ? route('usuarios.update', $usuario->id) : route('usuarios.store') }}" method="POST">
    @csrf
    @if(isset($usuario)) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="glass" style="padding: 2rem;">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.125rem;">Dados Pessoais</h3>
            
            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name ?? '') }}" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $usuario->cpf ?? '') }}" placeholder="000.000.000-00">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Telefone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $usuario->phone ?? '') }}" placeholder="(00) 00000-0000">
            </div>
        </div>

        <div class="glass" style="padding: 2rem;">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.125rem;">Acesso e Perfil</h3>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">E-mail Corporativo</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email ?? '') }}" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Perfil de Acesso</label>
                <select name="role" class="form-control">
                    <option value="user" {{ old('role', $usuario->role ?? '') == 'user' ? 'selected' : '' }}>Colaborador (User)</option>
                    <option value="manager" {{ old('role', $usuario->role ?? '') == 'manager' ? 'selected' : '' }}>Gestor (Manager)</option>
                    <option value="admin" {{ old('role', $usuario->role ?? '') == 'admin' ? 'selected' : '' }}>Administrador (Admin)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">{{ isset($usuario) ? 'Nova Senha (deixe em branco para manter)' : 'Senha de Acesso' }}</label>
                <input type="password" name="password" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Confirmar Senha</label>
                <input type="password" name="password_confirmation" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-primary" style="padding-left: 3rem; padding-right: 3rem;">
            {{ isset($usuario) ? 'Salvar Alterações' : 'Criar Usuário' }}
        </button>
    </div>
</form>

<style>
    .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted); }
</style>
@endsection
