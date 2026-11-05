<?php

namespace Tests\Unit\Services;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Livro;
use App\Repositories\Contracts\AssuntoRepositoryInterface;
use App\Repositories\Contracts\AutorRepositoryInterface;
use App\Repositories\Contracts\LivroRepositoryInterface;
use App\Services\LivroService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Tests\TestCase;

class LivroServiceTest extends TestCase
{
    private LivroRepositoryInterface&MockObject $livroRepository;

    private AutorRepositoryInterface&MockObject $autorRepository;

    private AssuntoRepositoryInterface&MockObject $assuntoRepository;

    private LivroService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->livroRepository = $this->createMock(LivroRepositoryInterface::class);
        $this->autorRepository = $this->createMock(AutorRepositoryInterface::class);
        $this->assuntoRepository = $this->createMock(AssuntoRepositoryInterface::class);

        $this->service = new LivroService(
            $this->livroRepository,
            $this->autorRepository,
            $this->assuntoRepository,
        );
    }

    public function test_criar_persiste_livro_e_sincroniza_relacionamentos(): void
    {
        $this->executarTransacao();

        $livro = new Livro(['Titulo' => 'Dom Casmurro']);

        $this->livroRepository
            ->expects($this->once())
            ->method('criar')
            ->with([
                'Titulo' => 'Dom Casmurro',
                'Editora' => 'Editora Exemplo',
                'Edicao' => 2,
                'AnoPublicacao' => '1899',
                'Valor' => '49.90',
            ])
            ->willReturn($livro);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAutores')
            ->with($livro, [10, 20]);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAssuntos')
            ->with($livro, [30]);

        $resultado = $this->service->criar([
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 2,
            'AnoPublicacao' => '1899',
            'Valor' => '49.90',
            'autores' => [10, 20],
            'assuntos' => [30],
        ]);

        $this->assertSame($livro, $resultado);
    }

    public function test_criar_envia_campos_opcionais_nulos_quando_ausentes(): void
    {
        $this->executarTransacao();

        $livro = new Livro(['Titulo' => 'Livro sem opcionais']);

        $this->livroRepository
            ->expects($this->once())
            ->method('criar')
            ->with([
                'Titulo' => 'Livro sem opcionais',
                'Editora' => null,
                'Edicao' => null,
                'AnoPublicacao' => null,
                'Valor' => '10.00',
            ])
            ->willReturn($livro);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAutores')
            ->with($livro, []);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAssuntos')
            ->with($livro, []);

        $this->service->criar([
            'Titulo' => 'Livro sem opcionais',
            'Valor' => '10.00',
        ]);
    }

    public function test_criar_traduz_query_exception_em_falha_na_operacao(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->livroRepository
            ->expects($this->once())
            ->method('criar')
            ->willThrowException($queryException);

        try {
            $this->service->criar([
                'Titulo' => 'Livro',
                'Valor' => '10.00',
            ]);

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível cadastrar o livro.', $e->getMessage());
            $this->assertTrue($e->devePreservarDados());
            $this->assertSame($queryException, $e->getPrevious());
        }
    }

    public function test_atualizar_persiste_livro_e_sincroniza_relacionamentos(): void
    {
        $this->executarTransacao();

        $livro = new Livro(['Titulo' => 'Título antigo']);

        $this->livroRepository
            ->expects($this->once())
            ->method('atualizar')
            ->with($livro, [
                'Titulo' => 'Título atualizado',
                'Editora' => 'Nova editora',
                'Edicao' => 3,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
            ])
            ->willReturn($livro);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAutores')
            ->with($livro, [5]);

        $this->livroRepository
            ->expects($this->once())
            ->method('sincronizarAssuntos')
            ->with($livro, [7, 8]);

        $this->service->atualizar($livro, [
            'Titulo' => 'Título atualizado',
            'Editora' => 'Nova editora',
            'Edicao' => 3,
            'AnoPublicacao' => '2024',
            'Valor' => '59.90',
            'autores' => [5],
            'assuntos' => [7, 8],
        ]);
    }

    public function test_excluir_usa_transacao_e_delega_para_repositorio(): void
    {
        $this->executarTransacao();

        $livro = new Livro(['Titulo' => 'Livro para excluir']);

        $this->livroRepository
            ->expects($this->once())
            ->method('excluir')
            ->with($livro)
            ->willReturn(true);

        $this->service->excluir($livro);
    }

    public function test_excluir_traduz_query_exception_sem_preservar_dados(): void
    {
        $this->executarTransacao();

        $queryException = $this->criarQueryException();

        $this->livroRepository
            ->expects($this->once())
            ->method('excluir')
            ->willThrowException($queryException);

        try {
            $this->service->excluir(new Livro(['Titulo' => 'Livro']));

            $this->fail('Era esperada uma FalhaNaOperacaoException.');
        } catch (FalhaNaOperacaoException $e) {
            $this->assertSame('Não foi possível excluir o livro.', $e->getMessage());
            $this->assertFalse($e->devePreservarDados());
            $this->assertSame($queryException, $e->getPrevious());
        }
    }

    public function test_listar_delega_para_repositorio(): void
    {
        $paginador = $this->createMock(LengthAwarePaginator::class);

        $this->livroRepository
            ->expects($this->once())
            ->method('listarPaginados')
            ->with(5)
            ->willReturn($paginador);

        $this->assertSame($paginador, $this->service->listar(5));
    }

    public function test_detalhar_carrega_relacionamentos(): void
    {
        $livro = new Livro(['Titulo' => 'Livro']);
        $livroCarregado = new Livro(['Titulo' => 'Livro']);

        $this->livroRepository
            ->expects($this->once())
            ->method('carregarRelacionamentos')
            ->with($livro)
            ->willReturn($livroCarregado);

        $this->assertSame($livroCarregado, $this->service->detalhar($livro));
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
            'insert into livro (Titulo) values (?)',
            ['Livro'],
            new RuntimeException('Falha no banco de dados')
        );
    }
}
