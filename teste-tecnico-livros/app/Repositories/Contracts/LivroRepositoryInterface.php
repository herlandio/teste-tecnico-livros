<?php

namespace App\Repositories\Contracts;

use App\Models\Livro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LivroRepositoryInterface
{
    public function listarPaginados(int $porPagina = 10): LengthAwarePaginator;

    public function criar(array $dados): Livro;

    public function atualizar(Livro $livro, array $dados): Livro;

    public function excluir(Livro $livro): bool;

    public function carregarRelacionamentos(Livro $livro): Livro;

    public function sincronizarAutores(Livro $livro, array $autores): void;

    public function sincronizarAssuntos(Livro $livro, array $assuntos): void;
}
