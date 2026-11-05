<?php

namespace App\Exceptions;

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

    public function devePreservarDados(): bool
    {
        return $this->preservarDados;
    }
}
