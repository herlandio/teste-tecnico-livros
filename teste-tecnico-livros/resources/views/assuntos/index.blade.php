
@extends('layouts.app')

@section('title', 'Assuntos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Assuntos</h2>
        <p class="text-muted mb-0">Gerencie os assuntos dos livros.</p>
    </div>

    <a href="{{ route('assuntos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo assunto
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($assuntos->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-tags text-muted fs-1"></i>
                <h5 class="mt-3">Nenhum assunto cadastrado</h5>
                <p class="text-muted">Cadastre o primeiro assunto.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Descrição</th>
                            <th>Livros associados</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assuntos as $assunto)
                            <tr>
                                <td class="fw-semibold">{{ $assunto->Descricao }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $assunto->livros_count }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('assuntos.show', $assunto) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('assuntos.edit', $assunto) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('assuntos.destroy', $assunto) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Deseja excluir este assunto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $assuntos->links() }}
        @endif
    </div>
</div>
@endsection
