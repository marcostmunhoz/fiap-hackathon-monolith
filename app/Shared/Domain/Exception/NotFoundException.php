<?php

namespace App\Shared\Domain\Exception;

use Throwable;

class NotFoundException extends DomainException
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Resource not found.', parent::HTTP_NOT_FOUND, $previous);
    }
}