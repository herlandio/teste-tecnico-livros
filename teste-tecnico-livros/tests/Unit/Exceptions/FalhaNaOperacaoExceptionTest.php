<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\FalhaNaOperacaoException;
use RuntimeException;
use Tests\TestCase;

class FalhaNaOperacaoExceptionTest extends TestCase
{
    public function test_nao_preserva_dados_por_padrao(): void
    {
        $excecao = new FalhaNaOperacaoException('Falha na operação.');

        $this->assertFalse($excecao->devePreservarDados());
    }

    public function test_preserva_dados_quando_configurado(): void
    {
        $excecao = new FalhaNaOperacaoException('Falha na operação.', true);

        $this->assertTrue($excecao->devePreservarDados());
    }

    public function test_mantem_a_excecao_original_como_causa(): void
    {
        $original = new RuntimeException('Erro original');

        $excecao = new FalhaNaOperacaoException('Falha na operação.', false, $original);

        $this->assertSame($original, $excecao->getPrevious());
    }
}
