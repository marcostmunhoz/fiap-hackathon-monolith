<?php

namespace App\User\Application\UseCase;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\User\Application\DTO\AuthenticateUserInput;
use App\User\Application\DTO\AuthenticateUserOutput;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;

readonly class AuthenticateUserUseCase
{
    private const int JWT_EXPIRATION_HOURS = 6;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private JwtGeneratorInterface $jwtGenerator,
    ) {
    }

    public function execute(AuthenticateUserInput $input): AuthenticateUserOutput
    {
        $user = $this->userRepository->findByEmail(new Email($input->email));

        if (!$user) {
            throw new NotFoundException('User not found.');
        }

        if (!$this->passwordHasher->verify($input->password, $user->hashedPassword)) {
            throw new UnauthenticatedException('Invalid credentials.');
        }

        $expiresAt = DateTime::now()->addHours(self::JWT_EXPIRATION_HOURS);
        $jwt = $this->jwtGenerator->generate(
            new JwtPayload($user->id, $expiresAt)
        );

        return new AuthenticateUserOutput($jwt, $expiresAt);
    }
}