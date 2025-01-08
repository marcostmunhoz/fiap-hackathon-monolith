<?php

namespace App\User\Domain\ValueObject;

use App\Shared\Domain\ValueObject\AbstractValueObject;

/**
 * @template-extends AbstractValueObject<string>
 */
readonly class Email extends AbstractValueObject
{
    protected function sanitize(mixed $value): string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->throwInvalidValueException('Invalid email address.');
        }

        return (string) $value;
    }
}