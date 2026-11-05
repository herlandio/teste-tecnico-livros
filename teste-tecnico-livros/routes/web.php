<?php

use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/livros', Response::HTTP_FOUND);

Route::resource('livros', LivroController::class);
Route::resource('autores', AutorController::class)
    ->parameters(['autores' => 'autor']);
Route::resource('assuntos', AssuntoController::class)
    ->parameters(['assuntos' => 'assunto']);
Route::get(
    '/relatorios/livros-por-autor',
    [RelatorioController::class, 'livrosPorAutor']
)->name('relatorios.livros-por-autor');
Route::get(
    '/relatorios/livros-por-autor/pdf',
    [RelatorioController::class, 'livrosPorAutorPdf']
)->name('relatorios.livros-por-autor.pdf');
