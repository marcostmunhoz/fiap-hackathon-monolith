<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function save(UserEntity $user): void;

    public function findByEmail(Email $email): ?UserEntity;
}