<?php

namespace Tests\Unit\User\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidValueException;
use App\User\Domain\ValueObject\FullName;

test('sanitize ensures the input is a 2-parts full name', function () {
    // Given
    $firstName = 'John';
    $lastName = 'Doe';
    $validInput = "{$firstName} {$lastName}";
    $invalidInput = 'John';

    // Then
    expect(new FullName($validInput))->not->toBeNull()
        ->firstName->toBe($firstName)
        ->lastName->toBe($lastName)
        ->and(static fn () => new FullName($invalidInput))
        ->toThrow(InvalidValueException::class, 'The full name must have at least 2 parts.');
});