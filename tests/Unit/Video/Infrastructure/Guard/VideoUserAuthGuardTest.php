<?php

namespace Tests\Unit\Video\Infrastructure\Guard;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\Video\Domain\Repository\VideoUserRepositoryInterface;
use App\Video\Infrastructure\Guard\VideoUserAuthGuard;
use Exception;
use Mockery;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->jwtGeneratorMock = Mockery::mock(JwtGeneratorInterface::class);
    $this->videoUserRepositoryMock = Mockery::mock(VideoUserRepositoryInterface::class);
    $this->sut = new VideoUserAuthGuard(
        $this->jwtGeneratorMock,
        $this->videoUserRepositoryMock
    );
});

test('authenticate throws when the token is not provided', function () {
    // Given
    $token = null;

    // When
    $this->sut->authenticate($token);
})->throws(UnauthenticatedException::class);

test('authenticate throws when the token is invalid', function () {
    // Given
    $token = 'invalid-token';
    $this->jwtGeneratorMock
        ->expects()
        ->parse($token)
        ->andThrow(new Exception('Invalid token.'));

    // When
    $this->sut->authenticate($token);
})->throws(UnauthenticatedException::class);

test('authenticate authenticates the user', function () {
    // Given
    $token = 'token';
    $user = getVideoUserEntity();
    $this->jwtGeneratorMock
        ->expects()
        ->parse($token)
        ->andReturn(new JwtPayload($user->id, DateTime::now()));
    $this->videoUserRepositoryMock
        ->expects()
        ->find($user->id)
        ->andReturn($user);

    // When
    $this->sut->authenticate($token);

    // Then
    expect($this->sut->check())->toBeTrue()
        ->and($this->sut->resolve())->toBe($user);
});

test('check returns false when the user is not authenticated', function () {
    // When
    $result = $this->sut->check();

    // Then
    expect($result)->toBeFalse();
});

test('resolve throws when the user is not authenticated', function () {
    // When
    $this->sut->resolve();
})->throws(UnauthenticatedException::class);