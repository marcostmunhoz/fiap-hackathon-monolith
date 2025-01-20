<?php

namespace App\Video\Application\DTO;

use App\Video\Domain\Entity\VideoEntity;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

/**
 * @phpstan-import-type TEntityArray from VideoEntity
 */
class ListUserVideosOutput extends Data
{
    public function __construct(
        /** @var VideoEntity[] */
        public array $videos
    ) {
    }

    /**
     * @return TEntityArray[]
     */
    public function transform(TransformationContext | TransformationContextFactory | null $transformationContext = null): array
    {
        return array_map(
            static fn (VideoEntity $video) => $video->toArray(),
            $this->videos
        );
    }
}