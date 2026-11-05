<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');

        $autores = Autor::all();
        $assuntos = Assunto::all();

        if ($autores->isEmpty() || $assuntos->isEmpty()) {
            return;
        }

        foreach (range(1, 25) as $indice) {
            $livro = Livro::create([
                'Titulo' => Str::limit($faker->unique()->realText(40), 40, ''),
                'Editora' => Str::limit($faker->company(), 40, ''),
                'Edicao' => $faker->numberBetween(1, 10),
                'AnoPublicacao' => (string) $faker->numberBetween(1950, 2025),
                'Valor' => $faker->randomFloat(2, 10, 200),
            ]);

            $quantidadeAutores = $indice === 1 ? 2 : $faker->numberBetween(1, 3);
            $quantidadeAssuntos = $indice === 1 ? 2 : $faker->numberBetween(1, 3);

            $livro->autores()->sync(
                $autores->random(min($quantidadeAutores, $autores->count()))->pluck('CodAu')->all()
            );

            $livro->assuntos()->sync(
                $assuntos->random(min($quantidadeAssuntos, $assuntos->count()))->pluck('codAs')->all()
            );
        }
    }
}
