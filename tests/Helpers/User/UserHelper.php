<?php

namespace Tests\Helpers\User;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Domain\ValueObject\FullName;
use App\User\Domain\Entity\UserEntity;
use Illuminate\Database\ConnectionInterface;

function getUserEntity(
    ?EntityId $id = null,
    ?FullName $name = null,
    ?Email $email = null,
    ?string $hashedPassword = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
): UserEntity {
    return new UserEntity(
        $id ?? new EntityId(faker()->uuid()),
        $name ?? new FullName('John Doe'),
        $email ?? new Email(faker()->safeEmail()),
        $hashedPassword ?? faker()->password(),
        $createdAt ?? new DateTime(now()),
        $updatedAt ?? new DateTime(now())
    );
}

function createUserEntity(?UserEntity $entity = null, ?ConnectionInterface $connection = null): UserEntity
{
    if (!$entity) {
        $entity = getUserEntity();
    }

    if (!$connection) {
        $connection = getConnection();
    }

    $connection->table('users')->insert($entity->toArray());

    return $entity;
}

function findUserEntity(EntityId $id, ?ConnectionInterface $connection = null): ?UserEntity
{
    if (!$connection) {
        $connection = getConnection();
    }

    $data = $connection
        ->table('users')
        ->where('id', (string) $id)
        ->first();

    if ($data === null) {
        return null;
    }

    return UserEntity::fromArray((array) $data);
}