<?php

namespace Tests\Feature;

use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_criam_dados_de_demonstracao(): void
    {
        $this->seed();

        $this->assertDatabaseCount('autor', 10);
        $this->assertDatabaseCount('assunto', 10);
        $this->assertDatabaseCount('livro', 25);

        $this->assertGreaterThanOrEqual(25, DB::table('livro_autor')->count());
        $this->assertGreaterThanOrEqual(25, DB::table('livro_assunto')->count());
    }

    public function test_seeders_criam_livro_com_multiplos_autores(): void
    {
        $this->seed();

        $this->assertTrue(Livro::has('autores', '>', 1)->exists());
    }
}
