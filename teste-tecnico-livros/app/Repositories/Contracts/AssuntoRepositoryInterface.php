<?php

namespace App\Repositories\Contracts;

use App\Models\Assunto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AssuntoRepositoryInterface
{
    public function listarPaginadosComLivros(int $porPagina = 10): LengthAwarePaginator;

    public function listarTodos(): Collection;

    public function criar(array $dados): Assunto;

    public function atualizar(Assunto $assunto, array $dados): Assunto;

    public function excluir(Assunto $assunto): bool;

    public function carregarLivros(Assunto $assunto): Assunto;
}
