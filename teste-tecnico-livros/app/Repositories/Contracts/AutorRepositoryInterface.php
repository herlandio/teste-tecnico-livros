<?php

namespace App\Repositories\Contracts;

use App\Models\Autor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AutorRepositoryInterface
{
    public function listarPaginadosComLivros(int $porPagina = 10): LengthAwarePaginator;

    public function listarTodos(): Collection;

    public function criar(array $dados): Autor;

    public function atualizar(Autor $autor, array $dados): Autor;

    public function excluir(Autor $autor): bool;

    public function carregarLivros(Autor $autor): Autor;
}
