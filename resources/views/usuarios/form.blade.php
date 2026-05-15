@extends('layouts.app')

@section('title', isset($usuario) ? 'Editar Usuário' : 'Novo Usuário')

@section('content')
<div class="info-block">
    <a href="{{ route('usuarios.index') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Voltar para a listagem
    </a>
    <h1 style="margin: 0.5rem 0 0 0;">{{ isset($usuario) ? 'Editar Usuário' : 'Criar Novo Usuário' }}</h1>
</div>

<form action="{{ isset($usuario) ? route('usuarios.update', $usuario->id) : route('usuarios.store') }}" method="POST">
    @csrf
    @if(isset($usuario)) @method('PUT') @endif

    <div class="grid-2nd">
        <div class="glass section-glass">
            <h3 class="card-title-small">Dados Pessoais</h3>
            
            <div class="form-group">
                <label class="form-label form-label-required">Nome Completo</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $usuario->cpf ?? '') }}" placeholder="000.000.000-00">
            </div>

            <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $usuario->phone ?? '') }}" placeholder="(00) 00000-0000">
            </div>
        </div>

        <div class="glass section-glass">
            <h3 class="card-title-small">Acesso e Perfil</h3>

            <div class="form-group">
                <label class="form-label form-label-required">E-mail Corporativo</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label form-label-required">Perfil de Acesso</label>
                <select name="role" class="form-control">
                    <option value="user" {{ old('role', $usuario->role ?? '') == 'user' ? 'selected' : '' }}>Colaborador (User)</option>
                    <option value="manager" {{ old('role', $usuario->role ?? '') == 'manager' ? 'selected' : '' }}>Gestor (Manager)</option>
                    <option value="admin" {{ old('role', $usuario->role ?? '') == 'admin' ? 'selected' : '' }}>Administrador (Admin)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label {{ isset($usuario) ? '' : 'form-label-required' }}">{{ isset($usuario) ? 'Nova Senha (deixe em branco para manter)' : 'Senha de Acesso' }}</label>
                <input type="password" name="password" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
            </div>

            <div class="form-group">
                <label class="form-label {{ isset($usuario) ? '' : 'form-label-required' }}">Confirmar Senha</label>
                <input type="password" name="password_confirmation" class="form-control" {{ isset($usuario) ? '' : 'required' }}>
            </div>
        </div>
    </div>

    <div class="td-actions td-actions-end" style="margin-top: 2rem;">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-primary" style="padding-left: 3rem; padding-right: 3rem;">
            {{ isset($usuario) ? 'Salvar Alterações' : 'Criar Usuário' }}
        </button>
    </div>
</form>
@endsection
