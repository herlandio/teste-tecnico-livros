<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface RelatorioRepositoryInterface
{
    public function livrosPorAutor(): Collection;
}
