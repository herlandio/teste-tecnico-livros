
@extends('layouts.app')

@section('title', 'Novo autor')

@section('content')
<div class="mb-4">
    <a href="{{ route('autores.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <h2 class="fw-bold mt-3">Cadastrar autor</h2>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('autores.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nome *</label>
                <input type="text" name="Nome"
                       class="form-control"
                       maxlength="40"
                       value="{{ old('Nome') }}"
                       required>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('autores.index') }}" class="btn btn-light">
                    Cancelar
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Salvar autor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
