<?php

namespace Tests\Feature;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_cadastrar_um_assunto(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'Descricao' => 'Literatura',
        ]);

        $response->assertRedirect(route('assuntos.index'));

        $this->assertDatabaseHas('assunto', [
            'Descricao' => 'Literatura',
        ]);
    }

    public function test_nao_permite_cadastrar_assunto_sem_descricao(): void
    {
        $response = $this->post(route('assuntos.store'), [
            'Descricao' => '',
        ]);

        $response->assertSessionHasErrors('Descricao');

        $this->assertDatabaseCount('assunto', 0);
    }

    public function test_usuario_pode_atualizar_um_assunto(): void
    {
        $assunto = Assunto::create([
            'Descricao' => 'Descrição antiga',
        ]);

        $response = $this->put(route('assuntos.update', $assunto), [
            'Descricao' => 'Descrição atualizada',
        ]);

        $response->assertRedirect(route('assuntos.index'));

        $this->assertDatabaseHas('assunto', [
            'codAs' => $assunto->codAs,
            'Descricao' => 'Descrição atualizada',
        ]);
    }

    public function test_usuario_pode_excluir_um_assunto(): void
    {
        $assunto = Assunto::create([
            'Descricao' => 'Assunto para excluir',
        ]);

        $response = $this->delete(route('assuntos.destroy', $assunto));

        $response->assertRedirect(route('assuntos.index'));

        $this->assertDatabaseMissing('assunto', [
            'codAs' => $assunto->codAs,
        ]);
    }
}