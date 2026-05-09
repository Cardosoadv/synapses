<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synapses GED - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid var(--success); color: #4ade80; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); color: #f87171; }
    </style>
</head>
<body>
    @auth
    <div class="sidebar">
        <h2 style="color: var(--primary); margin-bottom: 2rem;">Synapses <span style="font-weight: 300;">GED</span></h2>
        
        <nav>
            <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}" style="display: block; padding: 0.75rem; color: white; text-decoration: none; border-radius: 0.5rem; background: {{ request()->routeIs('usuarios.*') ? 'rgba(255,255,255,0.1)' : 'transparent' }};">
                <i class="bi bi-people" style="margin-right: 0.5rem;"></i> Usuários
            </a>
            
            <a href="{{ route('processos.index') }}" class="{{ request()->routeIs('processos.*') ? 'active' : '' }}" style="display: block; padding: 0.75rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-top: 0.5rem; background: {{ request()->routeIs('processos.*') ? 'rgba(255,255,255,0.1)' : 'transparent' }};">
                <i class="bi bi-folder" style="margin-right: 0.5rem;"></i> Processos
            </a>

            <a href="{{ route('tipos-processos.index') }}" class="{{ request()->routeIs('tipos-processos.*') ? 'active' : '' }}" style="display: block; padding: 0.75rem; color: white; text-decoration: none; border-radius: 0.5rem; margin-top: 0.5rem; background: {{ request()->routeIs('tipos-processos.*') ? 'rgba(255,255,255,0.1)' : 'transparent' }};">
                <i class="bi bi-tags" style="margin-right: 0.5rem;"></i> Tipos de Processos
            </a>

            <a href="#" style="display: block; padding: 0.75rem; color: var(--text-muted); text-decoration: none; margin-top: 0.5rem;">
                <i class="bi bi-file-earmark-text" style="margin-right: 0.5rem;"></i> Documentos
            </a>
        </nav>

        <div style="position: absolute; bottom: 2rem; width: calc(100% - 2rem);">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--border); margin-right: 0.75rem; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline" style="width: 100%; text-align: left;">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </form>
        </div>
    </div>
    @endauth

    <div class="@auth main-content @endauth">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
