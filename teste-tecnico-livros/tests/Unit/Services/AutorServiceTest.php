<?php

namespace Tests\Unit\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Autor;
use App\Repositories\Contracts\AutorRepositoryInterface;
use App\Services\AutorService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Tests\TestCase;

class AutorServiceTest extends TestCase
{
    private AutorRepositoryInterface&MockObject $autorRepository;

    private AutorService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->autorRepository = $this->createMock(AutorRepositoryInterface::class);

        $this->service = new AutorService($this->autorRepository);
    }

    public function test_criar_usa_transacao_e_delega_para_repositorio(): void
    {
        $this->executarTransacao();

        $autor = new Autor(['Nome' => 'Machado de Assis']);

        $this->autorRepository
            ->expects($this->once())
            ->method('criar')
            ->with(['Nome' => 'Machado de Assis'])
            ->willReturn($autor);

        $this->assertSame($autor, $this->service->criar(['Nome' => 'Machado de Assis']));
    }

    public function test_criar_traduz_query_exception_preservando_dados(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->autorRepository
            ->expects($this->once())
            ->method('criar')
            ->willThrowException($queryException);

        try {
            $this->service->criar(['Nome' => 'Autor']);

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível cadastrar o autor.', $e->getMessage());
            $this->assertTrue($e->devePreservarDados());
            $this->assertSame($queryException, $e->getPrevious());
        }
    }

    public function test_atualizar_delega_para_repositorio(): void
    {
        $this->executarTransacao();

        $autor = new Autor(['Nome' => 'Nome antigo']);

        $this->autorRepository
            ->expects($this->once())
            ->method('atualizar')
            ->with($autor, ['Nome' => 'Nome atualizado'])
            ->willReturn($autor);

        $this->service->atualizar($autor, ['Nome' => 'Nome atualizado']);
    }

    public function test_excluir_traduz_query_exception_sem_preservar_dados(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->autorRepository
            ->expects($this->once())
            ->method('excluir')
            ->willThrowException($queryException);

        try {
            $this->service->excluir(new Autor(['Nome' => 'Autor']));

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível excluir o autor.', $e->getMessage());
            $this->assertFalse($e->devePreservarDados());
            $this->assertSame($queryException, $e->getPrevious());
        }
    }

    private function executarTransacao(): void
    {
        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(fn (callable $callback) => $callback());
    }

    private function criarQueryException(): QueryException
    {
        return new QueryException(
            'sqlite',
            'insert into autor (Nome) values (?)',
            ['Autor'],
            new RuntimeException('Falha no banco de dados')
        );
    }
}
