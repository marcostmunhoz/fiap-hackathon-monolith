<?php

namespace App\User\Application\UseCase;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\FullName;
use App\User\Application\DTO\RegisterUserInput;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Exception\UserEmailAlreadyRegisteredException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;

readonly class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EntityIdGeneratorInterface $entityIdGenerator,
        private PasswordHasherInterface $passwordHasher
    ) {
    }

    public function execute(RegisterUserInput $input): void
    {
        $name = new FullName($input->name);
        $email = new Email($input->email);

        if ($this->userRepository->findByEmail($email)) {
            throw new UserEmailAlreadyRegisteredException();
        }

        $id = $this->entityIdGenerator->generate();
        $hashedPassword = $this->passwordHasher->hash($input->password);

        $user = new UserEntity(
            id: $id,
            name: $name,
            email: $email,
            hashedPassword: $hashedPassword,
        );

        $this->userRepository->save($user);
    }
}