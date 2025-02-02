<?php

namespace App\Video\Application\DTO;

use App\Shared\Domain\ValueObject\EntityId;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

class UploadUserVideoOutput extends Data
{
    public function __construct(
        public EntityId $id
    ) {
    }

    /**
     * @return array{id: string}
     */
    public function transform(TransformationContext | TransformationContextFactory | null $transformationContext = null): array
    {
        return ['id' => (string) $this->id];
    }
}