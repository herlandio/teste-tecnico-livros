
@extends('layouts.app')

@section('title', 'Detalhes do autor')

@section('content')
<div class="mb-4">
    <a href="{{ route('autores.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <h2 class="fw-bold mt-3">Detalhes do autor</h2>
</div>

<div class="card">
    <div class="card-body p-4">
        <h4>{{ $autor->Nome }}</h4>

        <hr>

        <h5 class="mb-3">Livros associados</h5>

        @forelse($autor->livros as $livro)
            <div class="border-bottom py-2">
                <a href="{{ route('livros.show', $livro) }}"
                   class="text-decoration-none">
                    {{ $livro->Titulo }}
                </a>
            </div>
        @empty
            <p class="text-muted">Este autor ainda não possui livros associados.</p>
        @endforelse
    </div>
</div>
@endsection
