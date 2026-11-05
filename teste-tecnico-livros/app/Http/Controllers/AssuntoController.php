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

    public function index(): Response
    {
        return response()->view('assuntos.index', [
            'assuntos' => $this->assuntoService->listar(),
        ], Response::HTTP_OK);
    }

    public function create(): Response
    {
        return response()->view('assuntos.create', [], Response::HTTP_OK);
    }

    public function store(AssuntoRequest $request): RedirectResponse
    {
        $this->assuntoService->criar($request->validated());

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto cadastrado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    public function show(Assunto $assunto): Response
    {
        return response()->view('assuntos.show', [
            'assunto' => $this->assuntoService->detalhar($assunto),
        ], Response::HTTP_OK);
    }

    public function edit(Assunto $assunto): Response
    {
        return response()->view('assuntos.edit', [
            'assunto' => $assunto,
        ], Response::HTTP_OK);
    }

    public function update(AssuntoRequest $request, Assunto $assunto): RedirectResponse
    {
        $this->assuntoService->atualizar($assunto, $request->validated());

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto atualizado com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }

    public function destroy(Assunto $assunto): RedirectResponse
    {
        $this->assuntoService->excluir($assunto);

        return redirect()
            ->route('assuntos.index')
            ->with('success', 'Assunto excluído com sucesso.')
            ->setStatusCode(Response::HTTP_SEE_OTHER);
    }
}
