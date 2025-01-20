<?php

namespace Tests\Helpers\Video;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Domain\ValueObject\FullName;
use App\Video\Domain\Entity\VideoEntity;
use App\Video\Domain\Entity\VideoUserEntity;
use App\Video\Domain\Enum\VideoStatus;
use Illuminate\Database\ConnectionInterface;

function getVideoUserEntity(
    ?EntityId $id = null,
    ?FullName $name = null,
    ?Email $email = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
): VideoUserEntity {
    return new VideoUserEntity(
        id: $id ?? new EntityId(faker()->uuid()),
        name: $name ?? new FullName('John Doe'),
        email: $email ?? new Email(faker()->safeEmail()),
        createdAt: $createdAt ?? new DateTime(now()),
        updatedAt: $updatedAt ?? new DateTime(now())
    );
}

function getVideoEntity(
    ?EntityId $id = null,
    ?string $filename = null,
    ?string $outputFilename = null,
    ?VideoStatus $status = null,
    ?EntityId $userId = null,
    ?DateTime $createdAt = null,
    ?DateTime $updatedAt = null
): VideoEntity {
    return new VideoEntity(
        id: $id ?? new EntityId(faker()->uuid()),
        filename: $filename ?? faker()->word(),
        userId: $userId ?? new EntityId(faker()->uuid()),
        status: $status ?? VideoStatus::PENDING,
        outputFilename: $outputFilename ?? faker()->word(),
        createdAt: $createdAt ?? new DateTime(now()),
        updatedAt: $updatedAt ?? new DateTime(now())
    );
}

function createVideoEntity(?VideoEntity $entity = null, ?ConnectionInterface $connection = null): VideoEntity
{
    if (!$entity) {
        $entity = getVideoEntity();
    }

    if (!$connection) {
        $connection = getConnection();
    }

    $connection->table('videos')->insert($entity->toArray());

    return $entity;
}

function findVideoEntity(EntityId $id, ?ConnectionInterface $connection = null): ?VideoEntity
{
    if (!$connection) {
        $connection = getConnection();
    }

    $data = $connection
        ->table('videos')
        ->where('id', (string) $id)
        ->first();

    if ($data === null) {
        return null;
    }

    return VideoEntity::fromArray((array) $data);
}
