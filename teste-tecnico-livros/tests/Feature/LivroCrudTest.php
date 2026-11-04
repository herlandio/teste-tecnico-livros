<?php

namespace Tests\Feature;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_cadastrar_livro_com_autor_e_assunto(): void
    {
        $autor = Autor::create([
            'Nome' => 'Machado de Assis',
        ]);

        $assunto = Assunto::create([
            'Descricao' => 'Literatura',
        ]);

        $response = $this->post(route('livros.store'), [
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '1899',
            'Valor' => '49.90',
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->codAs],
        ]);

        $response->assertRedirect(route('livros.index'));

        $this->assertDatabaseHas('livro', [
            'Titulo' => 'Dom Casmurro',
            'Valor' => '49.90',
        ]);

        $livro = Livro::where('Titulo', 'Dom Casmurro')->firstOrFail();

        $this->assertTrue(
            $livro->autores()->where('autor.CodAu', $autor->CodAu)->exists()
        );

        $this->assertTrue(
            $livro->assuntos()->where('assunto.codAs', $assunto->codAs)->exists()
        );
    }

    public function test_nao_permite_cadastrar_livro_sem_titulo(): void
    {
        $response = $this->post(route('livros.store'), [
            'Titulo' => '',
            'Valor' => '49.90',
        ]);

        $response->assertSessionHasErrors('Titulo');

        $this->assertDatabaseCount('livro', 0);
    }

    public function test_relatorio_consulta_view_e_exibe_livros_por_autor(): void
    {
        $autor = Autor::create([
            'Nome' => 'Machado de Assis',
        ]);

        $assunto = Assunto::create([
            'Descricao' => 'Literatura',
        ]);

        $livro = Livro::create([
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '1899',
            'Valor' => 49.90,
        ]);

        $livro->autores()->attach($autor->CodAu);
        $livro->assuntos()->attach($assunto->codAs);

        $response = $this->get(route('relatorios.livros-por-autor'));

        $response->assertOk();
        $response->assertSee('Machado de Assis');
        $response->assertSee('Dom Casmurro');
        $response->assertSee('Literatura');
    }
}
