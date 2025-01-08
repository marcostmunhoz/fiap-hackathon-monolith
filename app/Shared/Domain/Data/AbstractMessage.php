<?php

namespace App\Shared\Domain\Data;

use JsonSerializable;

/**
 * @codeCoverageIgnore
 */
readonly abstract class AbstractMessage implements JsonSerializable
{
    abstract public function jsonSerialize(): array;
}