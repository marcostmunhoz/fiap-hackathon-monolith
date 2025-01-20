<?php

namespace App\Shared\Domain\Exception;

use Throwable;

class MethodNotAllowedException extends DomainException
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Method not allowed.', parent::HTTP_METHOD_NOT_ALLOWED, $previous);
    }
}