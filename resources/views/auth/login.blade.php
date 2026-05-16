@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-page">
    <div class="glass login-card">
        <div class="login-header">
            <h1 class="login-title">Synapses</h1>
            <p class="login-subtitle">Gestão Eletrônica de Documentos</p>
        </div>

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="mb-1-5">
                <label class="form-label display-block form-label-required">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-2">
                <label class="form-label display-block form-label-required">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-full fs-1">
                Entrar no Sistema
            </button>
        </form>
    </div>
</div>
@endsection
