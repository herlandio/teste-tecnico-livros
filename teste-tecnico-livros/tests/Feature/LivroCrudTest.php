<?php

namespace Tests\Feature;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LivroCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_raiz_redireciona_para_lista_de_livros(): void
    {
        $this->get('/')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect('/livros');
    }

    public function test_paginas_de_livro_respondem_com_200(): void
    {
        $livro = Livro::create([
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '1899',
            'Valor' => 49.90,
        ]);

        $this->get(route('livros.index'))->assertStatus(Response::HTTP_OK);
        $this->get(route('livros.create'))->assertStatus(Response::HTTP_OK);
        $this->get(route('livros.show', $livro))->assertStatus(Response::HTTP_OK);
        $this->get(route('livros.edit', $livro))->assertStatus(Response::HTTP_OK);
    }

    public function test_livro_inexistente_retorna_404(): void
    {
        $this->get(route('livros.show', 999999))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

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

        $response->assertStatus(Response::HTTP_SEE_OTHER);
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

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors('Titulo');

        $this->assertDatabaseCount('livro', 0);
    }

    public function test_validacao_json_retorna_422(): void
    {
        $response = $this->postJson(route('livros.store'), [
            'Titulo' => '',
            'Valor' => '49.90',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('Titulo');
    }

    public function test_ano_de_publicacao_deve_estar_entre_1000_e_ano_atual(): void
    {
        $response = $this->post(route('livros.store'), [
            'Titulo' => 'Ano com 3 dígitos',
            'Valor' => '49.90',
            'AnoPublicacao' => '999',
        ]);

        $response->assertSessionHasErrors([
            'AnoPublicacao' => 'O ano de publicação deve ter 4 dígitos.',
        ]);

        $response = $this->post(route('livros.store'), [
            'Titulo' => 'Livro antigo',
            'Valor' => '49.90',
            'AnoPublicacao' => '0999',
        ]);

        $response->assertSessionHasErrors([
            'AnoPublicacao' => 'O ano de publicação deve ser maior ou igual a 1000.',
        ]);

        $response = $this->post(route('livros.store'), [
            'Titulo' => 'Livro do futuro',
            'Valor' => '49.90',
            'AnoPublicacao' => (string) (now()->year + 1),
        ]);

        $response->assertSessionHasErrors([
            'AnoPublicacao' => 'O ano de publicação não pode ser maior que '.now()->year.'.',
        ]);

        $this->assertDatabaseCount('livro', 0);
    }

    public function test_aceita_ano_de_publicacao_dentro_do_intervalo(): void
    {
        $response = $this->post(route('livros.store'), [
            'Titulo' => 'Livro atual',
            'Valor' => '49.90',
            'AnoPublicacao' => (string) now()->year,
        ]);

        $response->assertStatus(Response::HTTP_SEE_OTHER);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('livro', [
            'Titulo' => 'Livro atual',
            'AnoPublicacao' => (string) now()->year,
        ]);
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

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Machado de Assis');
        $response->assertSee('Dom Casmurro');
        $response->assertSee('Literatura');
    }

    public function test_usuario_pode_atualizar_um_livro(): void
    {
        $livro = Livro::create([
            'Titulo' => 'Título antigo',
            'Editora' => 'Editora antiga',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
            'Valor' => 30.00,
        ]);

        $response = $this->put(route('livros.update', $livro), [
            'Titulo' => 'Título atualizado',
            'Editora' => 'Nova editora',
            'Edicao' => 2,
            'AnoPublicacao' => '2025',
            'Valor' => '59.90',
            'autores' => [],
            'assuntos' => [],
        ]);

        $response->assertStatus(Response::HTTP_SEE_OTHER);
        $response->assertRedirect(route('livros.index'));

        $this->assertDatabaseHas('livro', [
            'Codl' => $livro->Codl,
            'Titulo' => 'Título atualizado',
            'Editora' => 'Nova editora',
            'Valor' => '59.90',
        ]);
    }

    public function test_usuario_pode_excluir_um_livro(): void
    {
        $livro = Livro::create([
            'Titulo' => 'Livro para excluir',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '2020',
            'Valor' => 40.00,
        ]);

        $response = $this->delete(route('livros.destroy', $livro));

        $response->assertStatus(Response::HTTP_SEE_OTHER);
        $response->assertRedirect(route('livros.index'));

        $this->assertDatabaseMissing('livro', [
            'Codl' => $livro->Codl,
        ]);
    }
}
