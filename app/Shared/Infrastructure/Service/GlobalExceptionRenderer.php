<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Domain\Exception\UnauthenticatedException;
use App\Shared\Domain\Exception\UnauthorizedException;
use App\Shared\Domain\Exception\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as BaseValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * @phpstan-import-type TMetadataArray from DomainException
 */
readonly class GlobalExceptionRenderer
{
    public function __construct(
        private ResponseFactory $responseFactory
    ) {
    }

    public function __invoke(Throwable $e, Request $request): JsonResponse
    {
        $mappedException = $this->mapKnownExceptions($e);

        if ($mappedException instanceof DomainException) {
            return $this->handleDomainException($mappedException);
        }

        return $this->handleUnhandledException();
    }

    /**
     * @return class-string<Throwable>[]
     */
    public function renders(): array
    {
        return [
            DomainException::class,
            AuthenticationException::class,
            AuthorizationException::class,
            BaseValidationException::class,
            NotFoundHttpException::class,
        ];
    }

    private function mapKnownExceptions(Throwable $e): DomainException | Throwable
    {
        return match (true) {
            $e instanceof AuthenticationException => new UnauthenticatedException(previous: $e),
            $e instanceof AuthorizationException => new UnauthorizedException(previous: $e),
            $e instanceof NotFoundHttpException => new NotFoundException(previous: $e),
            $e instanceof BaseValidationException => new ValidationException(previous: $e),
            default => $e
        };
    }

    /**
     * @param TMetadataArray $metadata
     */
    private function formatResponse(string $errorCode, string $message, array $metadata, int $status): JsonResponse
    {
        return $this->responseFactory->json([
            'error' => $errorCode,
            'message' => $message,
            'metadata' => $metadata,
        ], $status);
    }

    private function handleDomainException(DomainException $e): JsonResponse
    {
        return $this->formatResponse($e->getErrorCode(), $e->getMessage(), $e->getMetadata(), $e->getCode());
    }

    private function handleUnhandledException(): JsonResponse
    {
        return $this->formatResponse('UnhandledException', 'Unhandled error.', [], 500);
    }
}