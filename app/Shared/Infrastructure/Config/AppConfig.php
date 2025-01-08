<?php

namespace App\Shared\Infrastructure\Config;

use Illuminate\Contracts\Config\Repository;

/**
 * @codeCoverageIgnore
 */
readonly class AppConfig
{
    public function __construct(
        private Repository $config
    ) {
    }

    public function getAppName(): string
    {
        return $this->config->get('app.name');
    }

    public function getTimezone(): string
    {
        return $this->config->get('app.timezone');
    }

    public function getVersion(): string
    {
        return $this->config->get('app.version');
    }

    public function getJwtPrivateKey(): string
    {
        return base64_decode($this->config->get('app.jwt.private_key'));
    }

    public function getJwtPublicKey(): string
    {
        return base64_decode($this->config->get('app.jwt.public_key'));
    }

    public static function new(): self
    {
        return resolve(self::class);
    }
}