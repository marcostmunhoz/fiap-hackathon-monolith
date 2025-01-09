<?php

namespace App\Shared\Domain\Data;

use JsonSerializable;

/**
 * @codeCoverageIgnore
 */
readonly abstract class AbstractMessage implements JsonSerializable
{
    /**
     * @return array<string, mixed>
     */
    abstract public function jsonSerialize(): array;
}