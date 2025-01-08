<?php

namespace App\Shared\Domain\Exception;

use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends DomainException
{
    public function __construct(BaseValidationException $previous)
    {
        parent::__construct(
            'Validation failed.',
            parent::HTTP_UNPROCESSABLE_ENTITY,
            $previous,
            $previous->errors(),
        );
    }
}