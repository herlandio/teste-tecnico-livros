
@extends('layouts.app')

@section('title', 'Relatório de Livros por Autor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Relatório de Livros por Autor</h1>
        <p class="text-muted mb-0">
            Relação de livros, autores e assuntos cadastrados.
        </p>
    </div>

    <div class="d-flex gap-2">
        <a
            href="{{ route('relatorios.livros-por-autor.pdf') }}"
            class="btn btn-danger"
        >
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>

        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer"></i> Imprimir
        </button>
    </div>
</div>

@forelse ($autores as $livros)
    @php
        $primeiro = $livros->first();
    @endphp

    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>
                <i class="bi bi-person"></i>
                {{ $primeiro->autor_nome }}
            </strong>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <th>Editora</th>
                            <th>Edição</th>
                            <th>Ano</th>
                            <th>Valor</th>
                            <th>Assuntos</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($livros as $registro)
                            <tr>
                                <td>
                                    {{ $registro->livro_titulo ?? 'Sem livros cadastrados' }}
                                </td>

                                <td>{{ $registro->editora ?? '-' }}</td>
                                <td>{{ $registro->edicao ?? '-' }}</td>
                                <td>{{ $registro->ano_publicacao ?? '-' }}</td>

                                <td>
                                    @if ($registro->valor !== null)
                                        R$ {{ number_format((float) $registro->valor, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $registro->assuntos ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        Nenhum autor cadastrado.
    </div>
@endforelse
@endsection
