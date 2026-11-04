
@extends('layouts.app')

@section('title', 'Novo assunto')

@section('content')
<div class="mb-4">
    <a href="{{ route('assuntos.index') }}" class="text-decoration-none">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <h2 class="fw-bold mt-3">Cadastrar assunto</h2>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('assuntos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Descrição *</label>
                <input type="text" name="Descricao"
                       class="form-control"
                       maxlength="20"
                       value="{{ old('Descricao') }}"
                       required>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('assuntos.index') }}" class="btn btn-light">
                    Cancelar
                </a>
                <button class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Salvar assunto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
