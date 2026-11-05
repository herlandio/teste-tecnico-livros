<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\Livro;
use App\Services\Contracts\LivroServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LivroController extends Controller
{
    public function __construct(
        private readonly LivroServiceInterface $livroService,
    ) {}

    public function index(): Response
    {
        return response()->view('livros.index', [
            'livros' => $this->livroService->listar(),
        ], Response::HTTP_OK);
    }

    public function create(): Response
    {
        return response()->view('livros.create', [
            'autores' => $this->livroService->listarAutores(),
            'assuntos' => $this->livroService->listarAssuntos(),
        ], Response::HTTP_OK);
    }

    public function store(LivroRequest $request): RedirectResponse
    {
        $this->livroService->criar($request->validated());

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro cadastrado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    public function show(Livro $livro): Response
    {
        return response()->view('livros.show', [
            'livro' => $this->livroService->detalhar($livro),
        ], Response::HTTP_OK);
    }

    public function edit(Livro $livro): Response
    {
        return response()->view('livros.edit', [
            'livro' => $this->livroService->detalhar($livro),
            'autores' => $this->livroService->listarAutores(),
            'assuntos' => $this->livroService->listarAssuntos(),
        ], Response::HTTP_OK);
    }

    public function update(LivroRequest $request, Livro $livro): RedirectResponse
    {
        $this->livroService->atualizar($livro, $request->validated());

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro atualizado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    public function destroy(Livro $livro): RedirectResponse
    {
        $this->livroService->excluir($livro);

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro excluído com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }
}
