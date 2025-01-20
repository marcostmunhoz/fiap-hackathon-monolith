<?php

namespace App\Video\Domain\Entity;

use App\Shared\Domain\Entity\AbstractEntity;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use App\Video\Domain\Enum\VideoStatus;

/**
 * @phpstan-type TEntityArray array{
 *      id: string,
 *      filename: string,
 *      output_filename: string|null,
 *      status: string,
 *      user_id: string,
 *      created_at: string,
 *      updated_at: string,
 *  }
 * @template-extends AbstractEntity<TEntityArray>
 */
class VideoEntity extends AbstractEntity
{
    public function __construct(
        EntityId $id,
        public string $filename,
        public EntityId $userId,
        public VideoStatus $status = VideoStatus::PENDING,
        public ?string $outputFilename = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        parent::__construct($id, $createdAt, $updatedAt);
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'filename' => $this->filename,
            'output_filename' => $this->outputFilename,
            'status' => $this->status->value,
            'user_id' => (string) $this->userId,
            'created_at' => (string) $this->createdAt,
            'updated_at' => (string) $this->updatedAt,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: new EntityId($data['id']),
            filename: $data['filename'],
            userId: new EntityId($data['user_id']),
            status: VideoStatus::from($data['status']),
            outputFilename: $data['output_filename'],
            createdAt: DateTime::fromString($data['created_at']),
            updatedAt: DateTime::fromString($data['updated_at'])
        );
    }
}