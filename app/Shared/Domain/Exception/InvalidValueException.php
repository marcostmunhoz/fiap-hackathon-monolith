<?php

namespace App\Shared\Domain\Exception;

class InvalidValueException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, parent::HTTP_BAD_REQUEST);
    }
}