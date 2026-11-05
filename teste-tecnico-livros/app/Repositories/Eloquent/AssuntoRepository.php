<?php

namespace App\Repositories\Eloquent;

use App\Models\Assunto;
use App\Repositories\Contracts\AssuntoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AssuntoRepository implements AssuntoRepositoryInterface
{
    public function listarPaginadosComLivros(int $porPagina = 10): LengthAwarePaginator
    {
        return Assunto::withCount('livros')
            ->orderBy('Descricao')
            ->paginate($porPagina);
    }

    public function listarTodos(): Collection
    {
        return Assunto::orderBy('Descricao')->get();
    }

    public function criar(array $dados): Assunto
    {
        return Assunto::create($dados);
    }

    public function atualizar(Assunto $assunto, array $dados): Assunto
    {
        $assunto->update($dados);

        return $assunto;
    }

    public function excluir(Assunto $assunto): bool
    {
        return (bool) $assunto->delete();
    }

    public function carregarLivros(Assunto $assunto): Assunto
    {
        return $assunto->load('livros');
    }
}
