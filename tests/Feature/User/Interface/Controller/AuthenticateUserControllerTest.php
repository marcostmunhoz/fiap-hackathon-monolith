<?php

namespace Tests\Feature\User\Interface\Controller;

use App\Shared\Domain\ValueObject\DateTime;
use function Pest\Laravel\postJson;
use function Tests\Helpers\User\createUserEntity;
use function Tests\Helpers\User\getUserEntity;

test('it returns HTTP 200 containing the JWT on successful authentication', function () {
    // Given
    $now = DateTime::now();
    $user = getUserEntity(hashedPassword: 'hashed-password');
    createUserEntity($user);

    // When
    $response = postJson('users/authenticate', [
        'email' => (string) $user->email,
        'password' => 'hashed-password',
    ]);

    // Then
    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'token' => 'dummy-jwt',
                'expires_at' => $now->addHours(6)->getTimestamp(),
            ],
        ]);
});