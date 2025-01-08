<?php

namespace Tests\Unit\User\Domain\Entity;

use function Tests\Helpers\User\getUserEntity;

test('toArray returns the entity as an array', function () {
    // Given
    $user = getUserEntity();

    // When
    $result = $user->toArray();

    // Then
    expect($result)->toBe([
        'id' => (string) $user->id,
        'name' => (string) $user->name,
        'email' => (string) $user->email,
        'hashed_password' => $user->hashedPassword,
        'created_at' => (string) $user->createdAt,
        'updated_at' => (string) $user->updatedAt,
    ]);
});

test('hashedPassword is hidden from serialized entity', function () {
    // Given
    $user = getUserEntity();

    // When
    $result = $user->jsonSerialize();

    // Then
    expect($result)->not()->toContain('hashed_password');
});

test('fromArray creates a UserEntity from an array', function () {
    // Given
    $user = getUserEntity();

    // When
    $result = $user::fromArray($user->toArray());

    // Then
    expect($result)->id->equals($user->id)->toBeTrue()
        ->name->equals($user->name)->toBeTrue()
        ->email->equals($user->email)->toBeTrue()
        ->hashedPassword->toBe($user->hashedPassword)
        ->createdAt->equals($user->createdAt)->toBeTrue()
        ->updatedAt->equals($user->updatedAt)->toBeTrue();
});