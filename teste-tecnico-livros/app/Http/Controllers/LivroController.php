<?php

namespace App\Http\Controllers;

use App\Exceptions\FalhaNaOperacaoException;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::with(['autores', 'assuntos'])
            ->orderBy('Titulo')
            ->paginate(10);

        return view('livros.index', compact('livros'));
    }

    public function create()
    {
        $autores = Autor::orderBy('Nome')->get();
        $assuntos = Assunto::orderBy('Descricao')->get();

        return view('livros.create', compact('autores', 'assuntos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'Titulo' => ['required', 'string', 'max:40'],
            'Editora' => ['nullable', 'string', 'max:40'],
            'Edicao' => ['nullable', 'integer', 'min:1'],
            'AnoPublicacao' => ['nullable', 'digits:4'],
            'Valor' => ['required', 'numeric', 'min:0'],
            'autores' => ['nullable', 'array'],
            'autores.*' => ['integer', 'exists:autor,CodAu'],
            'assuntos' => ['nullable', 'array'],
            'assuntos.*' => ['integer', 'exists:assunto,codAs'],
        ]);

        try {
            DB::transaction(function () use ($dados) {
                $livro = Livro::create([
                    'Titulo' => $dados['Titulo'],
                    'Editora' => $dados['Editora'] ?? null,
                    'Edicao' => $dados['Edicao'] ?? null,
                    'AnoPublicacao' => $dados['AnoPublicacao'] ?? null,
                    'Valor' => $dados['Valor'],
                ]);

                $livro->autores()->sync($dados['autores'] ?? []);
                $livro->assuntos()->sync($dados['assuntos'] ?? []);
            });

            return redirect()
                ->route('livros.index')
                ->with('success', 'Livro cadastrado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível cadastrar o livro.',
                true,
                $e
            );
        }
    }

    public function show(Livro $livro)
    {
        $livro->load(['autores', 'assuntos']);

        return view('livros.show', compact('livro'));
    }

    public function edit(Livro $livro)
    {
        $livro->load(['autores', 'assuntos']);

        $autores = Autor::orderBy('Nome')->get();
        $assuntos = Assunto::orderBy('Descricao')->get();

        return view('livros.edit', compact('livro', 'autores', 'assuntos'));
    }

    public function update(Request $request, Livro $livro)
    {
        $dados = $request->validate([
            'Titulo' => ['required', 'string', 'max:40'],
            'Editora' => ['nullable', 'string', 'max:40'],
            'Edicao' => ['nullable', 'integer', 'min:1'],
            'AnoPublicacao' => ['nullable', 'digits:4'],
            'Valor' => ['required', 'numeric', 'min:0'],
            'autores' => ['nullable', 'array'],
            'autores.*' => ['integer', 'exists:autor,CodAu'],
            'assuntos' => ['nullable', 'array'],
            'assuntos.*' => ['integer', 'exists:assunto,codAs'],
        ]);

        try {
            DB::transaction(function () use ($dados, $livro) {
                $livro->update([
                    'Titulo' => $dados['Titulo'],
                    'Editora' => $dados['Editora'] ?? null,
                    'Edicao' => $dados['Edicao'] ?? null,
                    'AnoPublicacao' => $dados['AnoPublicacao'] ?? null,
                    'Valor' => $dados['Valor'],
                ]);

                $livro->autores()->sync($dados['autores'] ?? []);
                $livro->assuntos()->sync($dados['assuntos'] ?? []);
            });

            return redirect()
                ->route('livros.index')
                ->with('success', 'Livro atualizado com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível atualizar o livro.',
                true,
                $e
            );
        }
    }

    public function destroy(Livro $livro)
    {
        try {
            $livro->delete();

            return redirect()
                ->route('livros.index')
                ->with('success', 'Livro excluído com sucesso.');

        } catch (QueryException $e) {
            report($e);

            throw new FalhaNaOperacaoException(
                'Não foi possível excluir o livro.',
                false,
                $e
            );
        }
    }
}
