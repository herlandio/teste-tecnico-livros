<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');

        foreach (range(1, 10) as $indice) {
            Autor::firstOrCreate([
                'Nome' => Str::limit($faker->unique()->name(), 40, ''),
            ]);
        }
    }
}
