<?php

namespace App\Video\Infrastructure\Repository;

use App\Shared\Infrastructure\Repository\AbstractQueryBuilderRepository;
use App\Video\Domain\Entity\VideoEntity;
use App\Video\Domain\Repository\VideoRepositoryInterface;

class QueryBuilderVideoRepository extends AbstractQueryBuilderRepository implements VideoRepositoryInterface
{
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

    protected function getTable(): string
    {
        return 'videos';
    }
}