<?php

namespace Database\Seeders;

use App\Models\Assunto;
use Illuminate\Database\Seeder;

class AssuntoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');

        $descricoes = [
            'Literatura',
            'Romance',
            'Clássico',
            'Contos',
            'Distopia',
            'Fantasia',
            'Ficção Científica',
            'Biografia',
            'História',
            'Poesia',
        ];

        foreach (range(1, 10) as $indice) {
            Assunto::firstOrCreate([
                'Descricao' => $faker->unique()->randomElement($descricoes),
            ]);
        }
    }
}
