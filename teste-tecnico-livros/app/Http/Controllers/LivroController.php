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

    /**
     * Lista de livros
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return response()->view('livros.index', [
            'livros' => $this->livroService->listar(),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de criação de livro
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        return response()->view('livros.create', [
            'autores' => $this->livroService->listarAutores(),
            'assuntos' => $this->livroService->listarAssuntos(),
        ], Response::HTTP_OK);
    }

    /**
     * Armazena um novo livro
     *
     * @param  \App\Http\Requests\LivroRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LivroRequest $request): RedirectResponse
    {
        $this->livroService->criar($request->validated());

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro cadastrado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Exibe os detalhes de um livro
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function show(Livro $livro): Response
    {
        return response()->view('livros.show', [
            'livro' => $this->livroService->detalhar($livro),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de edição de livro
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function edit(Livro $livro): Response
    {
        return response()->view('livros.edit', [
            'livro' => $this->livroService->detalhar($livro),
            'autores' => $this->livroService->listarAutores(),
            'assuntos' => $this->livroService->listarAssuntos(),
        ], Response::HTTP_OK);
    }

    /**
     * Atualiza um livro
     *
     * @param  \App\Http\Requests\LivroRequest  $request
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LivroRequest $request, Livro $livro): RedirectResponse
    {
        $this->livroService->atualizar($livro, $request->validated());

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro atualizado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Remove um livro
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Livro $livro): RedirectResponse
    {
        $this->livroService->excluir($livro);

        return redirect()
            ->route('livros.index')
            ->with('success', 'Livro excluído com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }
}
