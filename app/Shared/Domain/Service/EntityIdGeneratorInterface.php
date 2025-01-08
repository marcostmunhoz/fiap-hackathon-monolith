<?php

namespace App\Shared\Domain\Service;

use App\Shared\Domain\ValueObject\EntityId;

interface EntityIdGeneratorInterface
{
    public function generate(): EntityId;
}