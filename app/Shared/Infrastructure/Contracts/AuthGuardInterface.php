<?php

namespace App\Shared\Infrastructure\Contracts;

/**
 * @template TUser
 */
interface AuthGuardInterface
{
    public function check(): bool;

    /**
     * @return TUser
     */
    public function resolve(): mixed;
}