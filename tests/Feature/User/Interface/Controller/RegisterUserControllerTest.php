<?php

namespace Tests\Feature\User\Interface\Controller;

use function Pest\Laravel\postJson;
use function Tests\Helpers\User\getUserEntity;

test('it returns HTTP 201 on successful registration', function () {
    // Given
    $user = getUserEntity();

    // When
    $response = postJson('users/register', [
        'email' => (string) $user->email,
        'password' => 'P@ssw0rd1234',
        'name' => (string) $user->name,
    ]);

    // Then
    $response->assertStatus(201);
});