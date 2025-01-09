<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Infrastructure\Config\AppConfig;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * @template-extends AbstractValueObject<DateTimeInterface>
 */
readonly class DateTime extends AbstractValueObject
{
    public function __toString(): string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }

    public function equals(AbstractValueObject $other): bool
    {
        return (string) $this === (string) $other;
    }

    public function getTimestamp(): int
    {
        return $this->value->getTimestamp();
    }

    public function addHours(int $hours): self
    {
        return new self(Carbon::instance($this->value)->addHours($hours));
    }

    protected function sanitize(mixed $value): DateTimeImmutable
    {
        if (!$value instanceof DateTimeInterface) {
            $this->throwInvalidValueException('Invalid date time.');
        }

        return DateTimeImmutable::createFromInterface($value)->setTimezone(self::getDefaultTimezone());
    }

    public static function now(): self
    {
        // Using Carbon in order to allow mocking the current time in tests
        return new self(Carbon::now(self::getDefaultTimezone()));
    }

    public static function fromString(string $date): self
    {
        return new self(new DateTimeImmutable($date, self::getDefaultTimezone()));
    }

    public static function fromTimestamp(int $timestamp): self
    {
        return new self(new DateTimeImmutable('@'.$timestamp)->setTimezone(self::getDefaultTimezone()));
    }

    private static function getDefaultTimezone(): DateTimeZone
    {
        return new DateTimeZone(AppConfig::new()->getTimezone());
    }
}