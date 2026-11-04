
@extends('layouts.app')

@section('title', 'Livros')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Livros</h2>
        <p class="text-muted mb-0">Gerencie o catálogo de livros.</p>
    </div>

    <a href="{{ route('livros.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo livro
    </a>
</div>

<div class="card">
    <div class="card-body">

        @if($livros->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-book text-muted fs-1"></i>
                <h5 class="mt-3">Nenhum livro cadastrado</h5>
                <p class="text-muted">Comece adicionando um livro ao catálogo.</p>

                <a href="{{ route('livros.create') }}" class="btn btn-primary">
                    Cadastrar primeiro livro
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Editora</th>
                            <th>Edição</th>
                            <th>Ano</th>
                            <th>Valor</th>
                            <th>Autores</th>
                            <th>Assuntos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($livros as $livro)
                            <tr>
                                <td class="fw-semibold">{{ $livro->Titulo }}</td>
                                <td>{{ $livro->Editora ?? '-' }}</td>
                                <td>{{ $livro->Edicao ?? '-' }}</td>
                                <td>{{ $livro->AnoPublicacao ?? '-' }}</td>
                                <td>
                                    {{ 'R$ ' . number_format((float) $livro->Valor, 2, ',', '.') }}
                                </td>
                                <td>
                                    {{ $livro->autores->pluck('Nome')->join(', ') ?: '-' }}
                                </td>
                                <td>
                                    {{ $livro->assuntos->pluck('Descricao')->join(', ') ?: '-' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('livros.show', $livro) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('livros.edit', $livro) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('livros.destroy', $livro) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Deseja realmente excluir este livro?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $livros->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
