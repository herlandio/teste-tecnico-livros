<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function livrosPorAutorPdf()
    {
        $registros = DB::table('vw_livros_por_autor')
            ->orderBy('autor_nome')
            ->orderBy('livro_titulo')
            ->get();

        $autores = $registros->groupBy('autor_id');

        $pdf = Pdf::loadView(
            'relatorios.livros-por-autor-pdf',
            compact('autores')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }
}
