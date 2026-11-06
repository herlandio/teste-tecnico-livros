<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Models\Autor;
use App\Services\Contracts\AutorServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AutorController extends Controller
{
    public function __construct(
        private readonly AutorServiceInterface $autorService,
    ) {}

    /**
     * Lista de autores
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return response()->view('autores.index', [
            'autores' => $this->autorService->listar(),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de criação de autor
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        return response()->view('autores.create', [], Response::HTTP_OK);
    }

    /**
     * Armazena um novo autor
     *
     * @param  \App\Http\Requests\AutorRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AutorRequest $request): RedirectResponse
    {
        $this->autorService->criar($request->validated());

        return redirect()
            ->route('autores.index')
            ->with('success', 'Autor cadastrado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Exibe os detalhes de um autor
     *
     * @param  \App\Models\Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function show(Autor $autor): Response
    {
        return response()->view('autores.show', [
            'autor' => $this->autorService->detalhar($autor),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de edição de autor
     *
     * @param  \App\Models\Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function edit(Autor $autor): Response
    {
        return response()->view('autores.edit', [
            'autor' => $autor,
        ], Response::HTTP_OK);
    }

    /**
     * Atualiza um autor
     *
     * @param  \App\Http\Requests\AutorRequest  $request
     * @param  \App\Models\Autor  $autor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AutorRequest $request, Autor $autor): RedirectResponse
    {
        $this->autorService->atualizar($autor, $request->validated());

        return redirect()
            ->route('autores.index')
            ->with('success', 'Autor atualizado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Remove um autor
     *
     * @param  \App\Models\Autor  $autor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Autor $autor): RedirectResponse
    {
        $this->autorService->excluir($autor);

        return redirect()
            ->route('autores.index')
            ->with('success', 'Autor excluído com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }
}
