<?php

namespace App\Services\Contracts;

use App\Models\Assunto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AssuntoServiceInterface
{
    public function listar(int $porPagina = 10): LengthAwarePaginator;

    public function detalhar(Assunto $assunto): Assunto;

    public function criar(array $dados): Assunto;

    public function atualizar(Assunto $assunto, array $dados): Assunto;

    public function excluir(Assunto $assunto): void;
}
