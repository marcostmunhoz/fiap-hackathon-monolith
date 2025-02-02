<?php

namespace App\Video\Domain\Repository;

use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Domain\Entity\VideoEntity;

interface VideoRepositoryInterface
{
    public function find(EntityId $id): VideoEntity;

    public function save(VideoEntity $video): void;

    /**
     * @return VideoEntity[]
     */
    public function list(EntityId $userId, int $page, int $perPage): array;
}