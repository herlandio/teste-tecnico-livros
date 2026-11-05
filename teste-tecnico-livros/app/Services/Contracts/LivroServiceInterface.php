<?php

namespace App\Services\Contracts;

use App\Models\Livro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LivroServiceInterface
{
    public function listar(int $porPagina = 10): LengthAwarePaginator;

    public function listarAutores(): Collection;

    public function listarAssuntos(): Collection;

    public function detalhar(Livro $livro): Livro;

    public function criar(array $dados): Livro;

    public function atualizar(Livro $livro, array $dados): Livro;

    public function excluir(Livro $livro): void;
}
