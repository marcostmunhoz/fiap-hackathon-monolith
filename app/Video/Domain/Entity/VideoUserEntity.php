<?php

namespace App\Video\Domain\Entity;

use App\Shared\Domain\Entity\AbstractEntity;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Domain\ValueObject\FullName;

/**
 * @template-extends AbstractEntity<array{
 *     id: string,
 *     name: string,
 *     email: string,
 *     created_at: string,
 *     updated_at: string,
 * }>
 */
class VideoUserEntity extends AbstractEntity
{
    public function __construct(
        EntityId $id,
        public FullName $name,
        public Email $email,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        parent::__construct($id, $createdAt, $updatedAt);
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'created_at' => (string) $this->createdAt,
            'updated_at' => (string) $this->updatedAt,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            new EntityId($data['id']),
            new FullName($data['name']),
            new Email($data['email']),
            DateTime::fromString($data['created_at']),
            DateTime::fromString($data['updated_at'])
        );
    }
}