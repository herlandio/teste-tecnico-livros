<?php

namespace App\Providers;

use App\Contracts\RelatorioPdfInterface;
use App\Infrastructure\Pdf\DomPdfRelatorioPdf;
use App\Services\AssuntoService;
use App\Services\AutorService;
use App\Services\Contracts\AssuntoServiceInterface;
use App\Services\Contracts\AutorServiceInterface;
use App\Services\Contracts\LivroServiceInterface;
use App\Services\Contracts\RelatorioServiceInterface;
use App\Services\LivroService;
use App\Services\RelatorioService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            RelatorioPdfInterface::class,
            DomPdfRelatorioPdf::class
        );

        $this->app->bind(
            LivroServiceInterface::class,
            LivroService::class
        );

        $this->app->bind(
            AutorServiceInterface::class,
            AutorService::class
        );

        $this->app->bind(
            AssuntoServiceInterface::class,
            AssuntoService::class
        );

        $this->app->bind(
            RelatorioServiceInterface::class,
            RelatorioService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
