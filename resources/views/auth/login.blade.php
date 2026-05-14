@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="h-100vh d-flex flex-center bg-login-gradient">
    <div class="glass w-full max-w-400 p-2-5">
        <div class="text-center mb-2">
            <h1 class="text-primary mb-0-5">Synapses</h1>
            <p class="text-muted">Gestão Eletrônica de Documentos</p>
        </div>

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="mb-1-5">
                <label class="d-block mb-0-5 fs-0-875 text-muted">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-2">
                <label class="d-block mb-0-5 fs-0-875 text-muted">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-full fs-1">
                Entrar no Sistema
            </button>
        </form>
    </div>
</div>
@endsection
