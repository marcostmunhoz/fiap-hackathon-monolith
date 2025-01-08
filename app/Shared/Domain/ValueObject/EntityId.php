<?php

namespace App\Shared\Domain\ValueObject;

/**
 * @template-extends AbstractValueObject<string>
 */
readonly class EntityId extends AbstractValueObject
{
    private const string UUID_REGEX = '/^[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}$/i';

    protected function sanitize(mixed $value): string
    {
        if (!preg_match(self::UUID_REGEX, $value)) {
            $this->throwInvalidValueException('Invalid entity ID.');
        }

        return (string) $value;
    }
}