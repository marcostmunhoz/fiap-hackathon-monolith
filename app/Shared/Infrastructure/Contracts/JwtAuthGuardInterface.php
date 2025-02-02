<?php

namespace App\Shared\Infrastructure\Contracts;

/**
 * @template TUser
 *
 * @template-extends AuthGuardInterface<TUser>
 */
interface JwtAuthGuardInterface extends AuthGuardInterface
{
    public function authenticate(?string $token): void;
}