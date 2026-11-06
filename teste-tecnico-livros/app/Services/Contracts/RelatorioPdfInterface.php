<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

interface RelatorioPdfInterface
{
    public function gerar(Collection $autores, string $nomeArquivo): Response;
}
