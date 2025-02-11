<?php

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidValueException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Infrastructure\Config\AppConfig;
use DateTime as NativeDateTime;
use DateTimeZone;
use function Pest\Laravel\instance;
use function Pest\Laravel\travelBack;
use function Pest\Laravel\travelTo;

beforeEach(function () {
    $appConfigMock = $this->createMock(AppConfig::class);
    $appConfigMock->method('getTimezone')->willReturn('UTC');
    instance(AppConfig::class, $appConfigMock);

    $this->date = new NativeDateTime('2025-01-01 00:00:00', new DateTimeZone('UTC'));
});

afterEach(function () {
    travelBack();
});

test('sanitize ensures the input is a valid DateTimeInterface', function () {
    // Given
    $invalidInput = '2025-01-01 00:00:00';

    // Then
    expect(new DateTime($this->date))->not->toBeNull()
        ->and(static fn () => new DateTime($invalidInput))->toThrow(InvalidValueException::class, 'Invalid date time.');
});

test('__toString returns the date in ATOM format', function () {
    // Given
    $dateTime = new DateTime($this->date);

    // When
    $result = (string) $dateTime;

    // Then
    expect($result)->toBe('2025-01-01T00:00:00+00:00');
});

test('equals returns true if the dates are the same date, time and timezone after conversion', function () {
    // Given
    $dateTimeWithUtcTimezone = new DateTime($this->date);
    $dateWithSaoPauloTimezone = new NativeDateTime('2024:12:31 21:00:00', new DateTimeZone('America/Sao_Paulo'));

    // When
    $result = $dateTimeWithUtcTimezone->equals(new DateTime($dateWithSaoPauloTimezone));

    // Then
    expect($result)->toBeTrue();
});

test('getTimestamp returns the timestamp of the date', function () {
    // Given
    $dateTime = new DateTime($this->date);

    // When
    $result = $dateTime->getTimestamp();

    // Then
    expect($result)->toBe(1735689600);
});

test('addHours returns a new instance with the added hours', function () {
    // Given
    $dateTime = new DateTime($this->date);

    // When
    $result = $dateTime->addHours(1);

    // Then
    expect((string) $result)->toBe('2025-01-01T01:00:00+00:00')
        ->and($dateTime)->not->toBe($result);
});

test('now returns a new instance with the current date and time with default timezone', function () {
    // Given
    travelTo($this->date);

    // When
    $result = DateTime::now();

    // Then
    expect((string) $result)->toBe('2025-01-01T00:00:00+00:00');
});

test('fromString returns a new instance from a string date with default timezone', function () {
    // Given
    $date = '2025-01-01 00:00:00';

    // When
    $result = DateTime::fromString($date);

    // Then
    expect((string) $result)->toBe('2025-01-01T00:00:00+00:00');
});

test('fromString returns a new instance applying the given timezone offset when present in the string date', function () {
    // Given
    $date = '2025-01-01 00:00:00 -03:00';

    // When
    $result = DateTime::fromString($date);

    // Then
    expect((string) $result)->toBe('2025-01-01T03:00:00+00:00');
});

test('fromTimestamp returns a new instance from a timestamp with default timezone', function () {
    // Given
    $timestamp = 1735689600;

    // When
    $result = DateTime::fromTimestamp($timestamp);

    // Then
    expect((string) $result)->toBe('2025-01-01T00:00:00+00:00');
});

it('assert true equals false', function () {
    expect(true)->toBe(false);
});
