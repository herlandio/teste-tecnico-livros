<?php

namespace App\Infrastructure\Pdf;

use App\Contracts\RelatorioPdfInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class DomPdfRelatorioPdf implements RelatorioPdfInterface
{
    public function gerar(Collection $autores, string $nomeArquivo): Response
    {
        $response = Pdf::loadView(
            'relatorios.livros-por-autor-pdf',
            ['autores' => $autores]
        )
            ->setPaper('a4', 'landscape')
            ->download($nomeArquivo);

        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
