<?php

namespace App\Video\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Infrastructure\Repository\AbstractQueryBuilderRepository;
use App\Video\Domain\Entity\VideoEntity;
use App\Video\Domain\Enum\VideoStatus;
use App\Video\Domain\Repository\VideoRepositoryInterface;

class QueryBuilderVideoRepository extends AbstractQueryBuilderRepository implements VideoRepositoryInterface
{
    public function find(EntityId $id): VideoEntity
    {
        $result = $this
            ->prepareQuery()
            ->where('id', (string) $id)
            ->firstOrFail();

        return new VideoEntity(
            new EntityId($result->id),
            $result->filename,
            new EntityId($result->user_id),
            VideoStatus::from($result->status),
            $result->output_filename,
            DateTime::fromString($result->created_at),
            DateTime::fromString($result->updated_at)
        );
    }

    public function save(VideoEntity $video): void
    {
        $data = $video->toArray();

        if ($this->entityExistsWithId($video->id)) {
            unset($data['id']);

            $this->prepareQuery()
                ->where('id', (string) $video->id)
                ->update($data);

            return;
        }

        $this->prepareQuery()->insert($data);
    }

    public function list(EntityId $userId, int $page, int $perPage): array
    {
        $result = $this
            ->prepareQuery()
            ->where('user_id', (string) $userId)
            ->take($perPage)
            ->skip(($page - 1) * $perPage)
            ->get();

        return array_map(
            static fn (object $data) => VideoEntity::fromArray((array) $data),
            $result->all()
        );
    }

    protected function getTable(): string
    {
        return 'videos';
    }
}