<?php

namespace Tests\Feature;

use App\Exceptions\FalhaNaOperacaoException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class FalhaNaOperacaoExceptionTest extends TestCase
{
    public function test_exibe_mensagem_e_preserva_dados_quando_configurado(): void
    {
        Route::middleware('web')->post('/_teste-excecao-preserva-dados', function () {
            throw new FalhaNaOperacaoException(
                'Não foi possível salvar os dados.',
                true
            );
        });

        $response = $this
            ->from('/formulario')
            ->post('/_teste-excecao-preserva-dados', [
                'Titulo' => 'Livro de teste',
            ]);

        $response
            ->assertStatus(Response::HTTP_SEE_OTHER)
            ->assertRedirect('/formulario')
            ->assertSessionHas('error', 'Não foi possível salvar os dados.')
            ->assertSessionHas('_old_input.Titulo', 'Livro de teste');
    }

    public function test_nao_preserva_dados_quando_configurado(): void
    {
        Route::middleware('web')->post('/_teste-excecao-sem-dados', function () {
            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o registro.',
                false
            );
        });

        $response = $this
            ->from('/formulario')
            ->post('/_teste-excecao-sem-dados', [
                'Titulo' => 'Livro de teste',
            ]);

        $response
            ->assertStatus(Response::HTTP_SEE_OTHER)
            ->assertRedirect('/formulario')
            ->assertSessionHas('error', 'Não foi possível excluir o registro.')
            ->assertSessionMissing('_old_input.Titulo');
    }

    public function test_erro_de_operacao_retorna_500_para_json(): void
    {
        Route::middleware('web')->post('/_teste-excecao-json', function () {
            throw new FalhaNaOperacaoException(
                'Não foi possível salvar os dados.'
            );
        });

        $response = $this->postJson('/_teste-excecao-json');

        $response
            ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => 'Não foi possível salvar os dados.',
            ]);
    }

    public function test_mantem_excecao_original_como_causa(): void
    {
        $original = new RuntimeException('Erro original do banco de dados');

        $excecao = new FalhaNaOperacaoException(
            'Não foi possível realizar a operação.',
            true,
            $original
        );

        $this->assertSame($original, $excecao->getPrevious());
    }
}
