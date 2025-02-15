<?php

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Infrastructure\Repository\AbstractQueryBuilderRepository;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Repository\UserRepositoryInterface;

class QueryBuilderUserRepository extends AbstractQueryBuilderRepository implements UserRepositoryInterface
{
    public function save(UserEntity $user): void
    {
        $data = $user->toArray();

        if ($this->entityExistsWithId($user->id)) {
            unset($data['id']);

            $this->prepareQuery()
                ->where('id', (string) $user->id)
                ->update($data);

            return;
        }

        $this->prepareQuery()->insert($data);
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $data = $this->prepareQuery()
            ->where('email', (string) $email)
            ->first();

        if ($data === null) {
            return null;
        }

        return UserEntity::fromArray((array) $data);
    }

    protected function getTable(): string
    {
        return 'users';
    }
}