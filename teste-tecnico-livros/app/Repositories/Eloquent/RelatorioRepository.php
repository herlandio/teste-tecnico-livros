<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RelatorioRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RelatorioRepository implements RelatorioRepositoryInterface
{
    public function livrosPorAutor(): Collection
    {
        return DB::table('vw_livros_por_autor')
            ->orderBy('autor_nome')
            ->orderBy('livro_titulo')
            ->get();
    }
}
