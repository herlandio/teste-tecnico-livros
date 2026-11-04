
@extends('layouts.app')

@section('title', 'Editar livro')

@section('content')

<div class="mb-4">
    <a href="{{ route('livros.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar para livros
    </a>

    <h2 class="fw-bold mt-3 mb-1">Editar livro</h2>
    <p class="text-muted">Atualize os dados e os relacionamentos do livro.</p>
</div>

<div class="card">
    <div class="card-body p-4">

        <form action="{{ route('livros.update', $livro) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Título *</label>
                    <input type="text" name="Titulo"
                           class="form-control"
                           maxlength="40"
                           value="{{ old('Titulo', $livro->Titulo) }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Editora</label>
                    <input type="text" name="Editora"
                           class="form-control"
                           maxlength="40"
                           value="{{ old('Editora', $livro->Editora) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Edição</label>
                    <input type="number" name="Edicao"
                           class="form-control"
                           min="1"
                           value="{{ old('Edicao', $livro->Edicao) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ano de publicação</label>
                    <input type="text" name="AnoPublicacao"
                           class="form-control"
                           maxlength="4"
                           inputmode="numeric"
                           pattern="[0-9]{4}"
                           value="{{ old('AnoPublicacao', $livro->AnoPublicacao) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Valor (R$) *</label>
                    <input type="number" name="Valor"
                           class="form-control"
                           min="0"
                           step="0.01"
                           value="{{ old('Valor', $livro->Valor) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Autores</label>
                    <select name="autores[]" class="form-select" multiple>
                        @foreach($autores as $autor)
                            <option value="{{ $autor->CodAu }}"
                                @selected(in_array($autor->CodAu, old('autores', $livro->autores->pluck('CodAu')->toArray())))>
                                {{ $autor->Nome }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Segure Ctrl (Windows) ou Command (Mac) para selecionar vários.
                    </small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Assuntos</label>
                    <select name="assuntos[]" class="form-select" multiple>
                        @foreach($assuntos as $assunto)
                            <option value="{{ $assunto->codAs }}"
                                @selected(in_array($assunto->codAs, old('assuntos', $livro->assuntos->pluck('codAs')->toArray())))>
                                {{ $assunto->Descricao }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('livros.index') }}" class="btn btn-light">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Atualizar livro
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
