<?php

namespace App\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Assunto;
use App\Repositories\Contracts\AssuntoRepositoryInterface;
use App\Services\Contracts\AssuntoServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AssuntoService implements AssuntoServiceInterface
{
    public function __construct(
        private readonly AssuntoRepositoryInterface $assuntoRepository,
    ) {}

    public function listar(int $porPagina = 10): LengthAwarePaginator
    {
        return $this->assuntoRepository->listarPaginadosComLivros($porPagina);
    }

    public function detalhar(Assunto $assunto): Assunto
    {
        return $this->assuntoRepository->carregarLivros($assunto);
    }

    public function criar(array $dados): Assunto
    {
        try {
            return DB::transaction(
                fn () => $this->assuntoRepository->criar($dados)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível cadastrar o assunto.',
                true,
                $e
            );
        }
    }

    public function atualizar(Assunto $assunto, array $dados): Assunto
    {
        try {
            return DB::transaction(
                fn () => $this->assuntoRepository->atualizar($assunto, $dados)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível atualizar o assunto.',
                true,
                $e
            );
        }
    }

    public function excluir(Assunto $assunto): void
    {
        try {
            DB::transaction(
                fn () => $this->assuntoRepository->excluir($assunto)
            );
        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o assunto.',
                false,
                $e
            );
        }
    }
}
