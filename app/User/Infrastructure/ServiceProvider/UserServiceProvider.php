<?php

namespace App\User\Infrastructure\ServiceProvider;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Infrastructure\Repository\QueryBuilderUserRepository;
use App\User\Infrastructure\Service\BcryptPasswordHasher;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            QueryBuilderUserRepository::class,
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            BcryptPasswordHasher::class
        );
    }
}