<?php

namespace App\Repositories\Eloquent;

use App\Models\Autor;
use App\Repositories\Contracts\AutorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AutorRepository implements AutorRepositoryInterface
{
    public function listarPaginadosComLivros(int $porPagina = 10): LengthAwarePaginator
    {
        return Autor::withCount('livros')
            ->orderBy('Nome')
            ->paginate($porPagina);
    }

    public function listarTodos(): Collection
    {
        return Autor::orderBy('Nome')->get();
    }

    public function criar(array $dados): Autor
    {
        return Autor::create($dados);
    }

    public function atualizar(Autor $autor, array $dados): Autor
    {
        $autor->update($dados);

        return $autor;
    }

    public function excluir(Autor $autor): bool
    {
        return (bool) $autor->delete();
    }

    public function carregarLivros(Autor $autor): Autor
    {
        return $autor->load('livros');
    }
}
