<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function livrosPorAutor()
    {
        $registros = DB::table('vw_livros_por_autor')
            ->orderBy('autor_nome')
            ->orderBy('livro_titulo')
            ->get();

        $autores = $registros->groupBy('autor_id');

        return view('relatorios.livros-por-autor', compact('autores'));
    }
}
