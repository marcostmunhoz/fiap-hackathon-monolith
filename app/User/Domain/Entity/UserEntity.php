<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\Entity\AbstractEntity;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\FullName;

/**
 * @template-extends AbstractEntity<array{
 *     id: string,
 *     name: string,
 *     email: string,
 *     hashed_password: string,
 *     created_at: string,
 *     updated_at: string,
 * }>
 */
class UserEntity extends AbstractEntity
{
    public function __construct(
        EntityId $id,
        public FullName $name,
        public Email $email,
        public string $hashedPassword,
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
            'hashed_password' => $this->hashedPassword,
            'created_at' => (string) $this->createdAt,
            'updated_at' => (string) $this->updatedAt,
        ];
    }

    protected function getPropertiesHiddenFromSerialization(): array
    {
        return ['hashed_password'];
    }

    public static function fromArray(array $data): static
    {
        return new self(
            new EntityId($data['id']),
            new FullName($data['name']),
            new Email($data['email']),
            $data['hashed_password'],
            DateTime::fromString($data['created_at']),
            DateTime::fromString($data['updated_at'])
        );
    }
}