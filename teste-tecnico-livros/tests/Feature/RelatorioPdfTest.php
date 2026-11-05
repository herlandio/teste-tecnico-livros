<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatorioPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_relatorio_pode_ser_exportado_em_pdf(): void
    {
        $autor = Autor::create([
            'Nome' => 'Machado de Assis',
        ]);

        $livro = Livro::create([
            'Titulo' => 'Dom Casmurro',
            'Editora' => 'Editora Exemplo',
            'Edicao' => 1,
            'AnoPublicacao' => '1899',
            'Valor' => 49.90,
        ]);

        $livro->autores()->attach($autor->CodAu);

        $response = $this->get(route('relatorios.livros-por-autor.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
