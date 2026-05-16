@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="glass login-card">
        <div class="login-header">
            <h1>Synapses</h1>
            <p>Gestão Eletrônica de Documentos</p>
        </div>

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label form-label-required">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group mb-2">
                <label class="form-label form-label-required">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block fs-1">
                Entrar no Sistema
            </button>
        </form>
    </div>
</div>
@endsection
