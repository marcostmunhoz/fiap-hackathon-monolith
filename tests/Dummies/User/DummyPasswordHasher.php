<?php

namespace Tests\Dummies\User;

use App\User\Domain\Service\PasswordHasherInterface;

readonly class DummyPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        return 'hashed-password';
    }

    public function verify(string $password, string $hash): bool
    {
        return 'hashed-password' === $hash;
    }
}