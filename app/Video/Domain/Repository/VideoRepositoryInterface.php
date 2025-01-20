<?php

namespace App\Video\Domain\Repository;

use App\Video\Domain\Entity\VideoEntity;

interface VideoRepositoryInterface
{
    public function save(VideoEntity $video): void;
}