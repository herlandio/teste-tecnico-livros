<?php

namespace Tests\Unit\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Assunto;
use App\Repositories\Contracts\AssuntoRepositoryInterface;
use App\Services\AssuntoService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Tests\TestCase;

class AssuntoServiceTest extends TestCase
{
    private AssuntoRepositoryInterface&MockObject $assuntoRepository;

    private AssuntoService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assuntoRepository = $this->createMock(AssuntoRepositoryInterface::class);

        $this->service = new AssuntoService($this->assuntoRepository);
    }

    public function test_criar_usa_transacao_e_delega_para_repositorio(): void
    {
        $this->executarTransacao();

        $assunto = new Assunto(['Descricao' => 'Literatura']);

        $this->assuntoRepository
            ->expects($this->once())
            ->method('criar')
            ->with(['Descricao' => 'Literatura'])
            ->willReturn($assunto);

        $this->assertSame($assunto, $this->service->criar(['Descricao' => 'Literatura']));
    }

    public function test_criar_traduz_query_exception_preservando_dados(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->assuntoRepository
            ->expects($this->once())
            ->method('criar')
            ->willThrowException($queryException);

        try {
            $this->service->criar(['Descricao' => 'Assunto']);

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível cadastrar o assunto.', $e->getMessage());
            $this->assertTrue($e->devePreservarDados());
            $this->assertSame($queryException, $e->getPrevious());
        }
    }

    public function test_atualizar_delega_para_repositorio(): void
    {
        $this->executarTransacao();

        $assunto = new Assunto(['Descricao' => 'Descrição antiga']);

        $this->assuntoRepository
            ->expects($this->once())
            ->method('atualizar')
            ->with($assunto, ['Descricao' => 'Descrição atualizada'])
            ->willReturn($assunto);

        $this->service->atualizar($assunto, ['Descricao' => 'Descrição atualizada']);
    }

    public function test_excluir_traduz_query_exception_sem_preservar_dados(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->assuntoRepository
            ->expects($this->once())
            ->method('excluir')
            ->willThrowException($queryException);

        try {
            $this->service->excluir(new Assunto(['Descricao' => 'Assunto']));

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível excluir o assunto.', $e->getMessage());
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
            'insert into assunto (Descricao) values (?)',
            ['Assunto'],
            new RuntimeException('Falha no banco de dados')
        );
    }
}
