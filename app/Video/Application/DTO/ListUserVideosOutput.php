<?php

namespace App\Video\Application\DTO;

use App\Video\Domain\Entity\VideoEntity;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

/**
 * @phpstan-type OutputArray array{
 *     id: string,
 *     status: string,
 * }[]
 */
class ListUserVideosOutput extends Data
{
    public function __construct(
        /** @var VideoEntity[] */
        public array $videos
    ) {
    }

    /**
     * @return OutputArray
     */
    public function transform(TransformationContext | TransformationContextFactory | null $transformationContext = null): array
    {
        return array_map(
            static fn (VideoEntity $video) => [
                'id' => (string) $video->id,
                'status' => $video->status->value,
            ],
            $this->videos
        );
    }
}