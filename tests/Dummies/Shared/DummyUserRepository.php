<?php

namespace Tests\Dummies\Shared;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class DummyUserRepository implements UserRepositoryInterface
{
    /** @var UserEntity[] */
    private static array $entities = [];

    public function save(UserEntity $user): void
    {
        foreach (self::$entities as &$entity) {
            if ($entity->id->equals($user->id)) {
                $entity = $user;

                return;
            }
        }

        unset($entity);

        self::addEntity($user);
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        return array_find(
            self::$entities,
            static fn ($entity) => $entity->email->equals($email)
        );
    }

    public static function addEntity(UserEntity $user): void
    {
        self::$entities[] = $user;
    }

    public static function reset(): void
    {
        self::$entities = [];
    }
}