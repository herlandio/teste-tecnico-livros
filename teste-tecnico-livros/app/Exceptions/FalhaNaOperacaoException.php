<?php

namespace App\Exceptions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Throwable;

class FalhaNaOperacaoException extends RuntimeException
{
     public function __construct(
        string $message,
        private bool $preservarDados = false,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function render(Request $request): RedirectResponse
    {
        $redirect = redirect()->back();

        if ($this->preservarDados) {
            $redirect->withInput();
        }

        return $redirect->with('error', $this->getMessage());
    }
}