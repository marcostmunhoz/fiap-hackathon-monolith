<?php

namespace App\Shared\Domain\Exception;

use Throwable;

class UnauthorizedException extends DomainException
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Unauthorized.', parent::HTTP_FORBIDDEN, $previous);
    }
}