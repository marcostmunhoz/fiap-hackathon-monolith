<?php

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidValueException;
use App\Shared\Domain\ValueObject\EntityId;

test('sanitize ensures the input is a valid uuid formatted string', function () {
    // Given
    $validInput = fake()->uuid();
    $invalidInput = 'some-string';

    // Then
    expect(new EntityId($validInput))->not->toBeNull()
        ->and(static fn () => new EntityId($invalidInput))->toThrow(InvalidValueException::class, 'Invalid entity ID.');
});