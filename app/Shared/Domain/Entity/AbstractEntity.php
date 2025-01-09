<?php

namespace App\Shared\Domain\Entity;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @template TEntityArray of array<string, mixed>
 * @template-implements Arrayable<string, mixed>
 *
 * @codeCoverageIgnore
 */
abstract class AbstractEntity implements Arrayable, JsonSerializable
{
    public function __construct(
        public protected(set) EntityId $id,
        public protected(set) ?DateTime $createdAt = null,
        public protected(set) ?DateTime $updatedAt = null
    ) {
        if ($this->createdAt === null) {
            $this->createdAt = DateTime::now();
        }

        if ($this->updatedAt === null) {
            $this->updatedAt = DateTime::now();
        }
    }

    /**
     * @return TEntityArray
     */
    abstract public function toArray(): array;

    /**
     * @return TEntityArray
     */
    public function jsonSerialize(): array
    {
        return array_filter(
            $this->toArray(),
            fn (string $property): bool => $this->shouldSerializeProperty($property)
        );
    }

    /**
     * @return string[]
     */
    protected function getPropertiesHiddenFromSerialization(): array
    {
        return [];
    }

    private function shouldSerializeProperty(string $property): bool
    {
        return !in_array($property, $this->getPropertiesHiddenFromSerialization());
    }

    /**
     * @param TEntityArray $data
     *
     * @return self<TEntityArray>
     */
    abstract public static function fromArray(array $data): self;
}