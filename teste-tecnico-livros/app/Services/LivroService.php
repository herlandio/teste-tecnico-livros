<?php

namespace App\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Livro;
use App\Repositories\Contracts\AssuntoRepositoryInterface;
use App\Repositories\Contracts\AutorRepositoryInterface;
use App\Repositories\Contracts\LivroRepositoryInterface;
use App\Services\Contracts\LivroServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class LivroService implements LivroServiceInterface
{
    public function __construct(
        private readonly LivroRepositoryInterface $livroRepository,
        private readonly AutorRepositoryInterface $autorRepository,
        private readonly AssuntoRepositoryInterface $assuntoRepository,
    ) {}

    public function listar(int $porPagina = 10): LengthAwarePaginator
    {
        return $this->livroRepository->listarPaginados($porPagina);
    }

    public function listarAutores(): Collection
    {
        return $this->autorRepository->listarTodos();
    }

    public function listarAssuntos(): Collection
    {
        return $this->assuntoRepository->listarTodos();
    }

    public function detalhar(Livro $livro): Livro
    {
        return $this->livroRepository->carregarRelacionamentos($livro);
    }

    public function criar(array $dados): Livro
    {
        try {
            return DB::transaction(function () use ($dados) {
                $livro = $this->livroRepository->criar($this->atributos($dados));

                $this->livroRepository->sincronizarAutores(
                    $livro,
                    $dados['autores'] ?? []
                );

                $this->livroRepository->sincronizarAssuntos(
                    $livro,
                    $dados['assuntos'] ?? []
                );

                return $livro;
            });
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível cadastrar o livro.',
                true,
                $e
            );
        }
    }

    public function atualizar(Livro $livro, array $dados): Livro
    {
        try {
            return DB::transaction(function () use ($livro, $dados) {
                $livro = $this->livroRepository->atualizar(
                    $livro,
                    $this->atributos($dados)
                );

                $this->livroRepository->sincronizarAutores(
                    $livro,
                    $dados['autores'] ?? []
                );

                $this->livroRepository->sincronizarAssuntos(
                    $livro,
                    $dados['assuntos'] ?? []
                );

                return $livro;
            });
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível atualizar o livro.',
                true,
                $e
            );
        }
    }

    public function excluir(Livro $livro): void
    {
        try {
            DB::transaction(
                fn () => $this->livroRepository->excluir($livro)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o livro.',
                false,
                $e
            );
        }
    }

    private function atributos(array $dados): array
    {
        return [
            'Titulo' => $dados['Titulo'],
            'Editora' => $dados['Editora'] ?? null,
            'Edicao' => $dados['Edicao'] ?? null,
            'AnoPublicacao' => $dados['AnoPublicacao'] ?? null,
            'Valor' => $dados['Valor'],
        ];
    }
}
