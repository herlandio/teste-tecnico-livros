<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RelatorioServiceInterface;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RelatorioController extends Controller
{
    public function __construct(
        private readonly RelatorioServiceInterface $relatorioService,
    ) {}

    public function livrosPorAutor(): Response
    {
        return response()->view('relatorios.livros-por-autor', [
            'autores' => $this->relatorioService->livrosAgrupadosPorAutor(),
        ], Response::HTTP_OK);
    }

    public function livrosPorAutorPdf(): SymfonyResponse
    {
        return $this->relatorioService->exportarPdf();
    }
}
