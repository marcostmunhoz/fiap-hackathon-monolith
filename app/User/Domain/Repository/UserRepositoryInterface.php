<?php

namespace App\User\Domain\Repository;

use App\Shared\Domain\ValueObject\Email;
use App\User\Domain\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function save(UserEntity $user): void;

    public function findByEmail(Email $email): ?UserEntity;
}