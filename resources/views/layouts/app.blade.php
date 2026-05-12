<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synapses GED - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
</head>
<body>
    @auth
    <div class="sidebar">
        <h2>Synapses <span style="font-weight: 300;">GED</span></h2>
        
        <nav>
            <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people" style="margin-right: 0.5rem;"></i> Usuários
            </a>
            
            <a href="{{ route('processos.index') }}" class="{{ request()->routeIs('processos.*') ? 'active' : '' }}">
                <i class="bi bi-folder" style="margin-right: 0.5rem;"></i> Processos
            </a>

            <a href="{{ route('tipos-processos.index') }}" class="{{ request()->routeIs('tipos-processos.*') ? 'active' : '' }}">
                <i class="bi bi-tags" style="margin-right: 0.5rem;"></i> Tipos de Processos
            </a>

            <a href="#">
                <i class="bi bi-file-earmark-text" style="margin-right: 0.5rem;"></i> Documentos
            </a>
        </nav>

        <div class="sidebar-profile">
            <div class="sidebar-profile-info">
                <div class="sidebar-profile-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div class="sidebar-profile-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-profile-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline sidebar-logout-btn">
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
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ asset('dist/js/app/app.core.js') }}"></script>
    <script src="{{ asset('dist/js/app/modules/app.processos.js') }}"></script>
    <script src="{{ asset('dist/js/app/modules/app.usuarios.js') }}"></script>
    @yield('scripts')
</body>
</html>
