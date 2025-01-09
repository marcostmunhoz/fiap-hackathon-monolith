<?php

namespace App\User\Application\DTO;

use App\Shared\Domain\ValueObject\DateTime;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

/**
 * @phpstan-type TArray array{token: string, expires_at: int}
 */
class AuthenticateUserOutput extends Data
{
    public function __construct(
        public string $token,
        public DateTime $expiresAt,
    ) {
    }

    /**
     * @return TArray
     */
    public function transform(TransformationContext | TransformationContextFactory | null $transformationContext = null): array
    {
        return [
            'token' => $this->token,
            'expires_at' => $this->expiresAt->getTimestamp(),
        ];
    }
}