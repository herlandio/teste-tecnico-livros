<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

interface RelatorioServiceInterface
{
    public function livrosAgrupadosPorAutor(): Collection;

    public function exportarPdf(): Response;
}
