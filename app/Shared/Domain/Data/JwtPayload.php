<?php

namespace App\Shared\Domain\Data;

use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;

readonly class JwtPayload
{
    public function __construct(
        public EntityId $id,
        public DateTime $expiresAt,
    ) {
    }
}