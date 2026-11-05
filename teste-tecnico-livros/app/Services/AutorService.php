<?php

namespace App\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Autor;
use App\Repositories\Contracts\AutorRepositoryInterface;
use App\Services\Contracts\AutorServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AutorService implements AutorServiceInterface
{
    public function __construct(
        private readonly AutorRepositoryInterface $autorRepository,
    ) {}

    public function listar(int $porPagina = 10): LengthAwarePaginator
    {
        return $this->autorRepository->listarPaginadosComLivros($porPagina);
    }

    public function detalhar(Autor $autor): Autor
    {
        return $this->autorRepository->carregarLivros($autor);
    }

    public function criar(array $dados): Autor
    {
        try {
            return DB::transaction(
                fn () => $this->autorRepository->criar($dados)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível cadastrar o autor.',
                true,
                $e
            );
        }
    }

    public function atualizar(Autor $autor, array $dados): Autor
    {
        try {
            return DB::transaction(
                fn () => $this->autorRepository->atualizar($autor, $dados)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível atualizar o autor.',
                true,
                $e
            );
        }
    }

    public function excluir(Autor $autor): void
    {
        try {
            DB::transaction(
                fn () => $this->autorRepository->excluir($autor)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o autor.',
                false,
                $e
            );
        }
    }
}
