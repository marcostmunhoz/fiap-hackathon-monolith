<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\JwtPayload;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Infrastructure\Config\AppConfig;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

readonly class SignedJwtGenerator implements JwtGeneratorInterface
{
    private const string ALGORITHM = 'RS256';

    public function __construct(
        private AppConfig $appConfig
    ) {
    }

    public function generate(JwtPayload $payload): string
    {
        $key = $this->appConfig->getJwtPrivateKey();
        $issuedAt = DateTime::now();

        return JWT::encode([
            'iss' => $this->appConfig->getAppName(),
            'aud' => $this->appConfig->getAppName(),
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $payload->expiresAt->getTimestamp(),
            'sub' => (string) $payload->id,
        ], $key, self::ALGORITHM);
    }

    public function parse(string $token): JwtPayload
    {
        $key = new Key($this->appConfig->getJwtPublicKey(), self::ALGORITHM);

        /** @var object{sub: string, exp: int} $decoded */
        // @phpstan-ignore-next-line varTag.nativeType
        $decoded = JWT::decode($token, $key);

        return new JwtPayload(
            new EntityId($decoded->sub),
            DateTime::fromTimestamp($decoded->exp),
        );
    }
}