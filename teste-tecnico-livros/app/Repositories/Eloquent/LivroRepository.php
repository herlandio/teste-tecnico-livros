<?php

namespace App\Repositories\Eloquent;

use App\Models\Livro;
use App\Repositories\Contracts\LivroRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LivroRepository implements LivroRepositoryInterface
{
    public function listarPaginados(int $porPagina = 10): LengthAwarePaginator
    {
        return Livro::with(['autores', 'assuntos'])
            ->orderBy('Titulo')
            ->paginate($porPagina);
    }

    public function criar(array $dados): Livro
    {
        return Livro::create($dados);
    }

    public function atualizar(Livro $livro, array $dados): Livro
    {
        $livro->update($dados);

        return $livro;
    }

    public function excluir(Livro $livro): bool
    {
        return (bool) $livro->delete();
    }

    public function carregarRelacionamentos(Livro $livro): Livro
    {
        return $livro->load(['autores', 'assuntos']);
    }

    public function sincronizarAutores(Livro $livro, array $autores): void
    {
        $livro->autores()->sync($autores);
    }

    public function sincronizarAssuntos(Livro $livro, array $assuntos): void
    {
        $livro->assuntos()->sync($assuntos);
    }
}
