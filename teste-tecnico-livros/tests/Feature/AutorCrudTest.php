<?php

namespace Tests\Feature;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_cadastrar_um_autor(): void
    {
        $response = $this->post(route('autores.store'), [
            'Nome' => 'Machado de Assis',
        ]        );

        $response->assertRedirect(route('autores.index'));

        $this->assertDatabaseHas('autor', [
            'Nome' => 'Machado de Assis',
        ]);
    }

    public function test_nao_permite_cadastrar_autor_sem_nome(): void
    {
        $response = $this->post(route('autores.store'), [
            'Nome' => '',
        ]);

        $response->assertSessionHasErrors('Nome');

        $this->assertDatabaseCount('autor', 0);
    }

    public function test_usuario_pode_atualizar_um_autor(): void
    {
        $autor = Autor::create([
            'Nome' => 'Nome antigo',
        ]);

        $response = $this->put(route('autores.update', $autor), [
            'Nome' => 'Nome atualizado',
        ]);

        $response->assertRedirect(route('autores.index'));

        $this->assertDatabaseHas('autor', [
            'CodAu' => $autor->CodAu,
            'Nome' => 'Nome atualizado',
        ]);
    }

    public function test_usuario_pode_excluir_um_autor(): void
    {
        $autor = Autor::create([
            'Nome' => 'Autor para excluir',
        ]);

        $response = $this->delete(route('autores.destroy', $autor));

        $response->assertRedirect(route('autores.index'));

        $this->assertDatabaseMissing('autor', [
            'CodAu' => $autor->CodAu,
        ]);
    }
}