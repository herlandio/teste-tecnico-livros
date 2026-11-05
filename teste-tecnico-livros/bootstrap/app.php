<?php

use App\Exceptions\FalhaNaOperacaoException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (FalhaNaOperacaoException $e, Request $request): SymfonyResponse {
            if ($request->expectsJson()) {
                return response()->json(
                    ['message' => $e->getMessage()],
                    SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
                );
            }

            $redirect = redirect()->back();

            if ($e->devePreservarDados()) {
                $redirect->withInput();
            }

            return $redirect
                ->with('error', $e->getMessage())
                ->setStatusCode(SymfonyResponse::HTTP_SEE_OTHER);
        });
    })->create();
