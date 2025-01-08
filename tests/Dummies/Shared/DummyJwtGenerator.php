<?php

namespace Tests\Dummies\Shared;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;

readonly class DummyJwtGenerator implements JwtGeneratorInterface
{
    public function __construct(
        private string $uuid
    ) {
    }

    public function generate(JwtPayload $payload): string
    {
        return 'dummy-jwt';
    }

    public function parse(string $token): JwtPayload
    {
        return new JwtPayload(
            new EntityId($this->uuid),
            DateTime::now()->addHours(6)
        );
    }
}