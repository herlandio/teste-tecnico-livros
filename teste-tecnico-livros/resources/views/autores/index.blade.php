
@extends('layouts.app')

@section('title', 'Autores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Autores</h2>
        <p class="text-muted mb-0">Gerencie os autores cadastrados.</p>
    </div>

    <a href="{{ route('autores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo autor
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($autores->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people text-muted fs-1"></i>
                <h5 class="mt-3">Nenhum autor cadastrado</h5>
                <p class="text-muted">Cadastre o primeiro autor.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Livros associados</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($autores as $autor)
                            <tr>
                                <td class="fw-semibold">{{ $autor->Nome }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $autor->livros_count }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('autores.show', $autor) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('autores.edit', $autor) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('autores.destroy', $autor) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Deseja excluir este autor?')">
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

            {{ $autores->links() }}
        @endif
    </div>
</div>
@endsection
