<?php

namespace Tests\Unit\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Infrastructure\Config\AppConfig;
use App\Shared\Infrastructure\Service\SignedJwtGenerator;
use Firebase\JWT\Key;
use Mockery;
use function Pest\Laravel\travelBack;
use function Pest\Laravel\travelTo;
use function PHPUnit\Framework\exactly;
use function PHPUnit\Framework\once;

beforeEach(function () {
    $this->jwtMock = mock('alias:Firebase\JWT\JWT');
    $this->appConfigMock = $this->createMock(AppConfig::class);
    $this->sut = new SignedJwtGenerator($this->appConfigMock);
});

afterEach(function () {
    travelBack();
});

test('generate calls the JWT library and returns the generated token', function () {
    // Given
    $now = DateTime::now();
    $expiresAt = $now->addHours(1);
    travelTo($now->value);
    $this->appConfigMock
        ->expects(once())
        ->method('getJwtPrivateKey')
        ->willReturn('private-key');
    $this->appConfigMock
        ->expects(exactly(2))
        ->method('getAppName')
        ->willReturn('app-name');
    $this->jwtMock
        ->allows('encode')
        ->andReturn('generated-token');
    $payload = new JwtPayload(
        new EntityId(fake()->uuid()),
        $expiresAt,
    );

    // When
    $result = $this->sut->generate($payload);

    // Then
    expect($result)->toBe('generated-token');
    $this->jwtMock
        ->shouldHaveReceived('encode')
        ->once()
        ->with([
            'iss' => 'app-name',
            'aud' => 'app-name',
            'iat' => $now->getTimestamp(),
            'exp' => $expiresAt->getTimestamp(),
            'sub' => (string) $payload->id,
        ], 'private-key', 'RS256');
});

test('parse calls the JWT library and returns the parsed payload', function () {
    // Given
    $uuid = fake()->uuid();
    $this->appConfigMock
        ->expects(once())
        ->method('getJwtPublicKey')
        ->willReturn('public-key');
    $this->jwtMock
        ->allows('decode')
        ->andReturn(
            (object) [
                'sub' => $uuid,
                'exp' => 1735689600,
            ]
        );

    // When
    $result = $this->sut->parse('token');

    // Then
    expect($result)->id->equals(new EntityId($uuid))->toBeTrue()
        ->expiresAt->getTimestamp()->toBe(1735689600);
    $this->jwtMock
        ->shouldHaveReceived('decode')
        ->once()
        ->with(
            'token',
            Mockery::on(static fn (Key $key) => $key->getKeyMaterial() === 'public-key' && $key->getAlgorithm() === 'RS256')
        );
});