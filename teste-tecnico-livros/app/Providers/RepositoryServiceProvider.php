<?php

namespace App\Providers;

use App\Repositories\Contracts\AssuntoRepositoryInterface;
use App\Repositories\Contracts\AutorRepositoryInterface;
use App\Repositories\Contracts\LivroRepositoryInterface;
use App\Repositories\Contracts\RelatorioRepositoryInterface;
use App\Repositories\Eloquent\AssuntoRepository;
use App\Repositories\Eloquent\AutorRepository;
use App\Repositories\Eloquent\LivroRepository;
use App\Repositories\Eloquent\RelatorioRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            LivroRepositoryInterface::class,
            LivroRepository::class
        );

        $this->app->bind(
            AutorRepositoryInterface::class,
            AutorRepository::class
        );

        $this->app->bind(
            AssuntoRepositoryInterface::class,
            AssuntoRepository::class
        );

        $this->app->bind(
            RelatorioRepositoryInterface::class,
            RelatorioRepository::class
        );
    }
}
