<?php

namespace App\Shared\Domain\Exception;

use RuntimeException;
use Throwable;

/**
 * @phpstan-type TMetadataArray array<array-key, mixed>
 */
class DomainException extends RuntimeException
{
    protected const int HTTP_BAD_REQUEST = 400;
    protected const int HTTP_UNAUTHORIZED = 401;
    protected const int HTTP_FORBIDDEN = 403;
    protected const int HTTP_NOT_FOUND = 404;
    protected const int HTTP_METHOD_NOT_ALLOWED = 405;
    protected const int HTTP_CONFLICT = 409;
    protected const int HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @param TMetadataArray $metadata
     */
    public function __construct(
        string $message,
        int $statusCode = self::HTTP_BAD_REQUEST,
        ?Throwable $previous = null,
        private readonly array $metadata = [],
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function getErrorCode(): string
    {
        return class_basename(static::class);
    }

    /**
     * @return TMetadataArray
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}