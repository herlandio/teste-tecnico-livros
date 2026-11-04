<?php

namespace App\Http\Controllers;

use App\Models\Assunto;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AssuntoController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::withCount('livros')
            ->orderBy('Descricao')
            ->paginate(10);

        return view('assuntos.index', compact('assuntos'));
    }

    public function create()
    {
        return view('assuntos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'Descricao' => ['required', 'string', 'max:20'],
        ]);

        try {
            Assunto::create($dados);

            return redirect()
                ->route('assuntos.index')
                ->with('success', 'Assunto cadastrado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível cadastrar o assunto.');
        }
    }

    public function show(Assunto $assunto)
    {
        $assunto->load('livros');

        return view('assuntos.show', compact('assunto'));
    }

    public function edit(Assunto $assunto)
    {
        return view('assuntos.edit', compact('assunto'));
    }

    public function update(Request $request, Assunto $assunto)
    {
        $dados = $request->validate([
            'Descricao' => ['required', 'string', 'max:20'],
        ]);

        try {
            $assunto->update($dados);

            return redirect()
                ->route('assuntos.index')
                ->with('success', 'Assunto atualizado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar o assunto.');
        }
    }

    public function destroy(Assunto $assunto)
    {
        try {
            $assunto->delete();

            return redirect()
                ->route('assuntos.index')
                ->with('success', 'Assunto excluído com sucesso.');

        } catch (QueryException $e) {
            report($e);

            return back()
                ->with('error', 'Não foi possível excluir o assunto.');
        }
    }
}
