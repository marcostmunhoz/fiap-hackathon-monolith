<?php

namespace App\User\Infrastructure\Service;

use App\User\Domain\Service\PasswordHasherInterface;
use Illuminate\Hashing\BcryptHasher;

readonly class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private BcryptHasher $bcryptHasher
    ) {
    }

    public function hash(string $password): string
    {
        return $this->bcryptHasher->make($password);
    }

    public function verify(string $password, string $hash): bool
    {
        return $this->bcryptHasher->check($password, $hash);
    }
}