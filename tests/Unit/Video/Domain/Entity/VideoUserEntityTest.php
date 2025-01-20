<?php

namespace Tests\Unit\Video\Domain\Entity;

use function Tests\Helpers\Video\getVideoUserEntity;

test('toArray returns the entity as an array', function () {
    // Given
    $user = getVideoUserEntity();

    // When
    $result = $user->toArray();

    // Then
    expect($result)->toBe([
        'id' => (string) $user->id,
        'name' => (string) $user->name,
        'email' => (string) $user->email,
        'created_at' => (string) $user->createdAt,
        'updated_at' => (string) $user->updatedAt,
    ]);
});

test('fromArray creates a VideoUserEntity from an array', function () {
    // Given
    $user = getVideoUserEntity();

    // When
    $result = $user::fromArray($user->toArray());

    // Then
    expect($result)->id->equals($user->id)->toBeTrue()
        ->name->equals($user->name)->toBeTrue()
        ->email->equals($user->email)->toBeTrue()
        ->createdAt->equals($user->createdAt)->toBeTrue()
        ->updatedAt->equals($user->updatedAt)->toBeTrue();
});