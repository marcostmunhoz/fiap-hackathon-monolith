<?php

namespace Tests\Unit\User\Infrastructure\Service;

use App\User\Infrastructure\Service\BcryptPasswordHasher;
use Illuminate\Hashing\BcryptHasher;

beforeEach(function () {
    $this->bcryptHasherMock = mock(BcryptHasher::class);
    $this->sut = new BcryptPasswordHasher($this->bcryptHasherMock);
});

test('hash returns the hashed password using BcryptHasher', function () {
    // Given
    $password = 'password';
    $this->bcryptHasherMock
        ->allows('make')
        ->andReturn('hashed-password');

    // When
    $hashedPassword = $this->sut->hash($password);

    // Then
    expect($hashedPassword)->toBe('hashed-password');
    $this->bcryptHasherMock
        ->shouldHaveReceived('make')
        ->once()
        ->with($password);
});

test('verify returns true when password matches hash', function () {
    // Given
    $password = 'password';
    $hash = 'hashed-password';
    $this->bcryptHasherMock
        ->allows('check')
        ->andReturn(true);

    // When
    $result = $this->sut->verify($password, $hash);

    // Then
    expect($result)->toBeTrue();
    $this->bcryptHasherMock
        ->shouldHaveReceived('check')
        ->once()
        ->with($password, $hash);
});