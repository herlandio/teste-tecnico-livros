<?php

namespace App\Services;

use App\Services\Contracts\RelatorioPdfInterface;
use App\Repositories\Contracts\RelatorioRepositoryInterface;
use App\Services\Contracts\RelatorioServiceInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class RelatorioService implements RelatorioServiceInterface
{
    public function __construct(
        private readonly RelatorioRepositoryInterface $relatorioRepository,
        private readonly RelatorioPdfInterface $relatorioPdf,
    ) {}

    public function livrosAgrupadosPorAutor(): Collection
    {
        return $this->relatorioRepository->livrosPorAutor()
            ->groupBy('autor_id');
    }

    public function exportarPdf(): Response
    {
        return $this->relatorioPdf->gerar(
            $this->livrosAgrupadosPorAutor(),
            'relatorio-livros-por-autor.pdf'
        );
    }
}
