
@extends('layouts.app')

@section('title', 'Detalhes do assunto')

@section('content')
<div class="mb-4">
    <a href="{{ route('assuntos.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <h2 class="fw-bold mt-3">Detalhes do assunto</h2>
</div>

<div class="card">
    <div class="card-body p-4">
        <h4>{{ $assunto->Descricao }}</h4>

        <hr>

        <h5 class="mb-3">Livros associados</h5>

        @forelse($assunto->livros as $livro)
            <div class="border-bottom py-2">
                <a href="{{ route('livros.show', $livro) }}"
                   class="text-decoration-none">
                    {{ $livro->Titulo }}
                </a>
            </div>
        @empty
            <p class="text-muted">Ainda não existem livros associados a este assunto.</p>
        @endforelse
    </div>
</div>
@endsection
