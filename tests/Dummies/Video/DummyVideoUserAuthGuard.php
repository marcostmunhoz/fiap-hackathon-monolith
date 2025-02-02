<?php

namespace Tests\Dummies\Video;

use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;

readonly class DummyVideoUserAuthGuard implements VideoUserAuthGuardInterface
{
    public function __construct(
        private VideoUserEntity $user
    ) {
    }

    public function check(): bool
    {
        return true;
    }

    public function resolve(): VideoUserEntity
    {
        return $this->user;
    }

    public function authenticate(?string $token): void
    {
        if ('video-user-token' !== $token) {
            throw new UnauthenticatedException();
        }
    }
}