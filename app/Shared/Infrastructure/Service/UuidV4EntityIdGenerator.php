<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\ValueObject\EntityId;
use Ramsey\Uuid\UuidFactory;

readonly class UuidV4EntityIdGenerator implements EntityIdGeneratorInterface
{
    public function __construct(
        private UuidFactory $factory
    ) {
    }

    public function generate(): EntityId
    {
        return new EntityId((string) $this->factory->uuid4());
    }
}