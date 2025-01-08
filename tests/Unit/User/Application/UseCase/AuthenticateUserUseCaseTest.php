<?php

namespace Tests\Unit\User\Application\UseCase;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Application\DTO\AuthenticateUserInput;
use App\User\Application\UseCase\AuthenticateUserUseCase;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Domain\ValueObject\Email;
use Mockery;
use function Pest\Laravel\travelTo;
use function Tests\Helpers\User\getUserEntity;

beforeEach(function () {
    $this->userRepositoryMock = mock(UserRepositoryInterface::class);
    $this->passwordHasherMock = mock(PasswordHasherInterface::class);
    $this->jwtGeneratorMock = mock(JwtGeneratorInterface::class);
    $this->sut = new AuthenticateUserUseCase(
        $this->userRepositoryMock,
        $this->passwordHasherMock,
        $this->jwtGeneratorMock
    );
});

it('throws NotFoundException when user can not be found with given email', function () {
    // Given
    $email = new Email('john.doe@exaple.com');
    $input = new AuthenticateUserInput($email, 'password');

    // Then
    $this->userRepositoryMock
        ->expects()
        ->findByEmail(
            Mockery::on(static fn (Email $param) => $param->equals($email))
        )
        ->andReturnNull();

    // When
    $this->sut->execute($input);
})->throws(NotFoundException::class, 'User not found.');

it('throws UnauthenticatedException when password does not match', function () {
    // Given
    $user = getUserEntity();
    $input = new AuthenticateUserInput($user->email, 'password');

    // Then
    $this->userRepositoryMock
        ->allows('findByEmail')
        ->andReturn($user);
    $this->passwordHasherMock
        ->expects()
        ->verify('password', $user->hashedPassword)
        ->andReturnFalse();

    // When
    $this->sut->execute($input);
})->throws(UnauthenticatedException::class, 'Invalid credentials.');

it('returns a valid JWT when user is authenticated', function () {
    // Given
    $now = DateTime::now();
    travelTo($now->value);
    $user = getUserEntity();
    $input = new AuthenticateUserInput($user->email, 'password');
    $token = 'token';
    $this->userRepositoryMock
        ->allows('findByEmail')
        ->andReturn($user);
    $this->passwordHasherMock
        ->allows('verify')
        ->andReturnTrue();
    $this->jwtGeneratorMock
        ->allows('generate')
        ->andReturn($token);

    // When
    $output = $this->sut->execute($input);

    // Then
    expect($output)->token->toBe($token)
        ->expiresAt->equals($now->addHours(6))->toBeTrue();
    $this->jwtGeneratorMock
        ->shouldHaveReceived('generate')
        ->once()
        ->with(
            Mockery::on(
                static fn (JwtPayload $param) => $param->id->equals($user->id) &&
                    $now->addHours(6)->equals($param->expiresAt)
            )
        );
});

