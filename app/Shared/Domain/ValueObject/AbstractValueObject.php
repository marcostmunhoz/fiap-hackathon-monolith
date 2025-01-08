<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidValueException;
use Stringable;

/**
 * @template T
 *
 * @property T $value
 */
readonly abstract class AbstractValueObject implements Stringable
{
    /**
     * @param T $value
     */
    public protected(set) mixed $value;

    /**
     * @param T $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $this->sanitize($value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * @param T $value
     *
     * @return T
     */
    abstract protected function sanitize(mixed $value): mixed;

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    protected function throwInvalidValueException(string $message): never
    {
        throw new InvalidValueException($message);
    }
}