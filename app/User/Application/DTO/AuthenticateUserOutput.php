<?php

namespace App\User\Application\DTO;

use App\Shared\Domain\ValueObject\DateTime;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

class AuthenticateUserOutput extends Data
{
    public function __construct(
        public string $token,
        public DateTime $expiresAt,
    ) {
    }

    public function transform(TransformationContext | TransformationContextFactory | null $transformationContext = null): array
    {
        return [
            'token' => $this->token,
            'expires_at' => $this->expiresAt->getTimestamp(),
        ];
    }
}