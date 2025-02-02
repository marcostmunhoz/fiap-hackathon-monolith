<?php

namespace App\Video\Infrastructure\Guard;

use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoUserRepositoryInterface;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;
use Throwable;

class VideoUserAuthGuard implements VideoUserAuthGuardInterface
{
    private ?VideoUserEntity $user = null;

    public function __construct(
        private readonly JwtGeneratorInterface $jwtGenerator,
        private readonly VideoUserRepositoryInterface $videoRepository
    ) {
    }

    public function authenticate(?string $token): void
    {
        if (!$token) {
            $this->throwUnauthenticatedException();
        }

        try {
            $payload = $this->jwtGenerator->parse($token);
        } catch (Throwable) {
            $this->throwUnauthenticatedException();
        }

        $this->user = $this->videoRepository->find($payload->id);
    }

    public function check(): bool
    {
        return null !== $this->user;
    }

    public function resolve(): VideoUserEntity
    {
        if (!$this->user) {
            $this->throwUnauthenticatedException();
        }

        return $this->user;
    }

    private function throwUnauthenticatedException(): never
    {
        throw new UnauthenticatedException();
    }
}