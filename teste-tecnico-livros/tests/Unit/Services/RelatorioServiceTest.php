<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\RelatorioRepositoryInterface;
use App\Services\Contracts\RelatorioPdfInterface;
use App\Services\RelatorioService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RelatorioServiceTest extends TestCase
{
    private RelatorioRepositoryInterface&MockObject $relatorioRepository;

    private RelatorioPdfInterface&MockObject $relatorioPdf;

    private RelatorioService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->relatorioRepository = $this->createMock(RelatorioRepositoryInterface::class);
        $this->relatorioPdf = $this->createMock(RelatorioPdfInterface::class);

        $this->service = new RelatorioService(
            $this->relatorioRepository,
            $this->relatorioPdf,
        );
    }

    public function test_livros_agrupados_por_autor(): void
    {
        $this->relatorioRepository
            ->expects($this->once())
            ->method('livrosPorAutor')
            ->willReturn($this->registros());

        $agrupados = $this->service->livrosAgrupadosPorAutor();

        $this->assertCount(2, $agrupados);
        $this->assertCount(2, $agrupados[1]);
        $this->assertCount(1, $agrupados[2]);
    }

    public function test_exportar_pdf_delega_para_o_gerador_com_dados_agrupados(): void
    {
        $this->relatorioRepository
            ->expects($this->once())
            ->method('livrosPorAutor')
            ->willReturn($this->registros());

        $response = new Response('conteudo-pdf');

        $this->relatorioPdf
            ->expects($this->once())
            ->method('gerar')
            ->with(
                $this->callback(function (Collection $autores): bool {
                    return $autores->keys()->all() === [1, 2];
                }),
                'relatorio-livros-por-autor.pdf'
            )
            ->willReturn($response);

        $this->assertSame($response, $this->service->exportarPdf());
    }

    private function registros(): Collection
    {
        return collect([
            (object) ['autor_id' => 1, 'autor_nome' => 'Machado de Assis', 'livro_id' => 10],
            (object) ['autor_id' => 1, 'autor_nome' => 'Machado de Assis', 'livro_id' => 11],
            (object) ['autor_id' => 2, 'autor_nome' => 'Clarice Lispector', 'livro_id' => 12],
        ]);
    }
}
