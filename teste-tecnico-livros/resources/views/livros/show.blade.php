
@extends('layouts.app')

@section('title', 'Detalhes do livro')

@section('content')

<div class="mb-4">
    <a href="{{ route('livros.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar para livros
    </a>

    <h2 class="fw-bold mt-3 mb-1">Detalhes do livro</h2>
</div>

<div class="card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold">{{ $livro->Titulo }}</h3>
                <p class="text-muted">{{ $livro->Editora ?? 'Editora não informada' }}</p>
            </div>

            <a href="{{ route('livros.edit', $livro) }}"
               class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>

        <hr>

        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted small">Edição</span>
                <p class="fw-semibold">{{ $livro->Edicao ?? '-' }}</p>
            </div>

            <div class="col-md-4">
                <span class="text-muted small">Ano de publicação</span>
                <p class="fw-semibold">{{ $livro->AnoPublicacao ?? '-' }}</p>
            </div>

            <div class="col-md-4">
                <span class="text-muted small">Valor</span>
                <p class="fw-semibold">
                    R$ {{ number_format((float) $livro->Valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="col-md-6">
                <span class="text-muted small">Autores</span>
                @forelse($livro->autores as $autor)
                    <p class="mb-1">{{ $autor->Nome }}</p>
                @empty
                    <p class="text-muted">Nenhum autor associado.</p>
                @endforelse
            </div>

            <div class="col-md-6">
                <span class="text-muted small">Assuntos</span>
                @forelse($livro->assuntos as $assunto)
                    <p class="mb-1">{{ $assunto->Descricao }}</p>
                @empty
                    <p class="text-muted">Nenhum assunto associado.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection
