<?php

namespace Tests\Unit\User\Application\UseCase;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\Service\MessageProducerInterface;
use App\Shared\Domain\ValueObject\Email;
use App\User\Application\DTO\RegisterUserInput;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Exception\UserEmailAlreadyRegisteredException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;
use Mockery;

beforeEach(function () {
    $this->userRepositoryMock = mock(UserRepositoryInterface::class);
    $this->entityIdGeneratorMock = mock(EntityIdGeneratorInterface::class);
    $this->passwordHasherMock = mock(PasswordHasherInterface::class);
    $this->messageProducerMock = mock(MessageProducerInterface::class);
    $this->sut = new RegisterUserUseCase(
        $this->userRepositoryMock,
        $this->entityIdGeneratorMock,
        $this->passwordHasherMock,
        $this->messageProducerMock
    );
});

it('throws DomainException when email is already registered', function () {
    // Given
    $email = new Email(faker()->safeEmail());
    $input = new RegisterUserInput(
        'John Doe',
        $email,
        'P@ssw0rd'
    );
    $entityMock = Mockery::mock(UserEntity::class);

    // Then
    $this->userRepositoryMock
        ->expects()
        ->findByEmail(
            Mockery::on(static fn (Email $param) => $param->equals($email))
        )
        ->andReturn($entityMock);

    // When
    $this->sut->execute($input);
})->throws(UserEmailAlreadyRegisteredException::class);