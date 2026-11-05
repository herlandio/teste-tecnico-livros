
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Cadastro de Livros') | Biblioteca</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('livros.index') }}">
                <i class="bi bi-book"></i> Biblioteca
            </a>

            <ul class="navbar-nav flex-row ms-auto gap-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('livros.index') }}">
                        <i class="bi bi-book"></i> Livros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('autores.index') }}">
                        <i class="bi bi-people"></i> Autores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('assuntos.index') }}">
                        <i class="bi bi-tags"></i> Assuntos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('relatorios.livros-por-autor') }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Relatórios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('relatorios.livros-por-autor.pdf') }}">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="bg-white border-top py-3 text-center text-muted">
        <small>Sistema de Cadastro de Livros — Desafio Técnico</small>
    </footer>

</body>
</html>
