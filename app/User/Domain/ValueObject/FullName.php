<?php

namespace App\User\Domain\ValueObject;

use App\Shared\Domain\ValueObject\AbstractValueObject;

/**
 * @template-extends AbstractValueObject<string>
 */
readonly class FullName extends AbstractValueObject
{
    // @phpstan-ignore-next-line property.uninitializedReadonly
    public private(set) string $firstName;
    // @phpstan-ignore-next-line property.uninitializedReadonly
    public private(set) string $lastName;

    protected function sanitize(mixed $value): string
    {
        $parts = array_filter(
            explode(' ', trim($value)),
            static fn (string $part) => !empty($part)
        );

        if (count($parts) < 2) {
            $this->throwInvalidValueException('The full name must have at least 2 parts.');
        }

        // @phpstan-ignore-next-line property.readonlyAssignedNotInConstructor
        $this->firstName = array_shift($parts);
        // @phpstan-ignore-next-line property.readonlyAssignedNotInConstructor
        $this->lastName = implode(' ', $parts);

        return "{$this->firstName} {$this->lastName}";
    }
}