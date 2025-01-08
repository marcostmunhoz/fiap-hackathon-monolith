<?php

namespace Tests\Unit\Shared\Infrastructure\Service;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Infrastructure\Service\GlobalExceptionRenderer;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    $this->responseFactoryMock = mock(ResponseFactory::class);
    $this->sut = new GlobalExceptionRenderer($this->responseFactoryMock);
});

it('renders the expected exceptions', function () {
    // When
    $renders = ($this->sut)->renders();

    // Then
    expect($renders)->toBe([
        DomainException::class,
        AuthenticationException::class,
        AuthorizationException::class,
        ValidationException::class,
        NotFoundHttpException::class,
    ]);
});

it('returns response on expected format for DomainException', function (DomainException $exception, array $expectedBody, int $expectedStatusCode) {
    // Given
    $expectedResponse = mock(JsonResponse::class);
    $this->responseFactoryMock
        ->allows('json')
        ->andReturn($expectedResponse);

    // When
    $response = ($this->sut)($exception, mock(Request::class));

    // Then
    expect($response)->toBe($expectedResponse);
    $this->responseFactoryMock
        ->shouldHaveReceived('json')
        ->once()
        ->with($expectedBody, $expectedStatusCode);
})->with([
    'base exception' => [
        new DomainException('Some message'),
        [
            'error' => 'DomainException',
            'message' => 'Some message',
            'metadata' => [],
        ],
        400,
    ],
    'child exception' => [
        new class() extends DomainException {
            public function __construct()
            {
                parent::__construct(
                    message: 'Some other message',
                    statusCode: 404,
                    metadata: ['some key' => ['some value 1']]
                );
            }

            public function getErrorCode(): string
            {
                return 'ChildDomainException';
            }
        },
        [
            'error' => 'ChildDomainException',
            'message' => 'Some other message',
            'metadata' => [
                'some key' => ['some value 1'],
            ],
        ],
        404,
    ],
]);

it('maps the exception to the corresponding DomainException', function (Exception $exception, array $expectedBody, int $expectedStatusCode) {
    // Given
    $expectedResponse = mock(JsonResponse::class);
    $this->responseFactoryMock
        ->allows('json')
        ->andReturn($expectedResponse);

    // When
    $response = ($this->sut)($exception, mock(Request::class));

    // Then
    expect($response)->toBe($expectedResponse);
    $this->responseFactoryMock
        ->shouldHaveReceived('json')
        ->once()
        ->with($expectedBody, $expectedStatusCode);
})->with([
    'AuthenticationException' => [
        new AuthenticationException('Some message'),
        [
            'error' => 'UnauthenticatedException',
            'message' => 'Unauthenticated.',
            'metadata' => [],
        ],
        401,
    ],
    'AuthorizationException' => [
        new AuthorizationException('Some message'),
        [
            'error' => 'UnauthorizedException',
            'message' => 'Unauthorized.',
            'metadata' => [],
        ],
        403,
    ],
    'NotFoundHttpException' => [
        new NotFoundHttpException('Some message'),
        [
            'error' => 'NotFoundException',
            'message' => 'Resource not found.',
            'metadata' => [],
        ],
        404,
    ],
    'ValidationException' => [
        fn () => ValidationException::withMessages(['some key' => ['some value 1']]),
        [
            'error' => 'ValidationException',
            'message' => 'Validation failed.',
            'metadata' => [
                'some key' => ['some value 1'],
            ],
        ],
        422,
    ],
]);

it('returns response on expected format for unhandled exceptions', function () {
    // Given
    $exception = new Exception('Some message');
    $expectedResponse = mock(JsonResponse::class);
    $this->responseFactoryMock
        ->allows('json')
        ->andReturn($expectedResponse);

    // When
    $response = ($this->sut)($exception, mock(Request::class));

    // Then
    expect($response)->toBe($expectedResponse);
    $this->responseFactoryMock
        ->shouldHaveReceived('json')
        ->once()
        ->with([
            'error' => 'UnhandledException',
            'message' => 'Unhandled error.',
            'metadata' => [],
        ], 500);
});