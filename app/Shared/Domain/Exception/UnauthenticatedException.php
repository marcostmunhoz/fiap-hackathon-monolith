<?php

namespace App\Shared\Domain\Exception;

use Throwable;

class UnauthenticatedException extends DomainException
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unauthenticated.', parent::HTTP_UNAUTHORIZED, $previous);
    }
}