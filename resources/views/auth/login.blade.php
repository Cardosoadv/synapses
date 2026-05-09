@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div style="height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at top right, #1e293b, #0f172a);">
    <div class="glass" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary); margin-bottom: 0.5rem;">Synapses</h1>
            <p style="color: var(--text-muted);">Gestão Eletrônica de Documentos</p>
        </div>

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1rem;">
                Entrar no Sistema
            </button>
        </form>
    </div>
</div>
@endsection
