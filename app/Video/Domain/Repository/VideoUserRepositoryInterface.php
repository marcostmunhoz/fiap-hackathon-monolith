<?php

namespace App\Video\Domain\Repository;

use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Domain\Entity\VideoUserEntity;

interface VideoUserRepositoryInterface
{
    public function find(EntityId $id): VideoUserEntity;
}