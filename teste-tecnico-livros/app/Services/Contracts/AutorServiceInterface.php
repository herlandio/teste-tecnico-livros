<?php

namespace App\Services\Contracts;

use App\Models\Autor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AutorServiceInterface
{
    public function listar(int $porPagina = 10): LengthAwarePaginator;

    public function detalhar(Autor $autor): Autor;

    public function criar(array $dados): Autor;

    public function atualizar(Autor $autor, array $dados): Autor;

    public function excluir(Autor $autor): void;
}
