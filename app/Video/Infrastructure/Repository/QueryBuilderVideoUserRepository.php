<?php

namespace App\Video\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Domain\ValueObject\FullName;
use App\Shared\Infrastructure\Repository\AbstractQueryBuilderRepository;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Repository\VideoUserRepositoryInterface;

class QueryBuilderVideoUserRepository extends AbstractQueryBuilderRepository implements VideoUserRepositoryInterface
{
    public function find(EntityId $id): VideoUserEntity
    {
        $result = $this
            ->prepareQuery()
            ->where('id', (string) $id)
            ->firstOrFail();

        return new VideoUserEntity(
            new EntityId($result->id),
            new FullName($result->name),
            new Email($result->email),
            DateTime::fromString($result->created_at),
            DateTime::fromString($result->updated_at)
        );
    }

    protected function getTable(): string
    {
        return 'users';
    }
}