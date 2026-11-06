<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;
use App\Services\Contracts\AssuntoServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AssuntoController extends Controller
{
    public function __construct(
        private readonly AssuntoServiceInterface $assuntoService,
    ) {}

    /**
     * Lista de assuntos
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return response()->view('assuntos.index', [
            'assuntos' => $this->assuntoService->listar(),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de criação de assunto
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        return response()->view('assuntos.create', [], Response::HTTP_OK);
    }

    /**
     * Armazena um novo assunto
     *
     * @param  \App\Http\Requests\AssuntoRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AssuntoRequest $request): RedirectResponse
    {
        $this->assuntoService->criar($request->validated());

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto cadastrado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Exibe os detalhes de um assunto
     *
     * @param  \App\Models\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */
    public function show(Assunto $assunto): Response
    {
        return response()->view('assuntos.show', [
            'assunto' => $this->assuntoService->detalhar($assunto),
        ], Response::HTTP_OK);
    }

    /**
     * Formulário de edição de assunto
     *
     * @param  \App\Models\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */   
    public function edit(Assunto $assunto): Response
    {
        return response()->view('assuntos.edit', [
            'assunto' => $assunto,
        ], Response::HTTP_OK);
    }

    /**
     * Atualiza um assunto
     *
     * @param  \App\Http\Requests\AssuntoRequest  $request
     * @param  \App\Models\Assunto  $assunto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AssuntoRequest $request, Assunto $assunto): RedirectResponse
    {
        $this->assuntoService->atualizar($assunto, $request->validated());

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto atualizado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    /**
     * Remove um assunto
     *
     * @param  \App\Models\Assunto  $assunto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Assunto $assunto): RedirectResponse
    {
        $this->assuntoService->excluir($assunto);

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto excluído com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }
}
