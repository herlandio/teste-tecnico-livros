<?php

namespace App\Http\Controllers;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AutorController extends Controller
{
    public function index()
    {
        $autores = Autor::withCount('livros')
            ->orderBy('Nome')
            ->paginate(10);

        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'Nome' => ['required', 'string', 'max:40'],
        ]);

        try {
            Autor::create($dados);

            return redirect()
                ->route('autores.index')
                ->with('success', 'Autor cadastrado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível cadastrar o autor.',
                true,
                $e
            );
        }
    }

    public function show(Autor $autor)
    {
        $autor->load('livros');

        return view('autores.show', compact('autor'));
    }

    public function edit(Autor $autor)
    {
        return view('autores.edit', compact('autor'));
    }

    public function update(Request $request, Autor $autor)
    {
        $dados = $request->validate([
            'Nome' => ['required', 'string', 'max:40'],
        ]);

        try {
            $autor->update($dados);

            return redirect()
                ->route('autores.index')
                ->with('success', 'Autor atualizado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível atualizar o autor.',
                true,
                $e
            );
        }
    }

    public function destroy(Autor $autor)
    {
        try {
            $autor->delete();

            return redirect()
                ->route('autores.index')
                ->with('success', 'Autor excluído com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o autor.',
                false,
                $e
            );
        }
    }
}
