<?php

namespace App\Shared\Domain\Service;

use App\Shared\Domain\Data\JwtPayload;

interface JwtGeneratorInterface
{
    public function generate(JwtPayload $payload): string;

    public function parse(string $token): JwtPayload;
}