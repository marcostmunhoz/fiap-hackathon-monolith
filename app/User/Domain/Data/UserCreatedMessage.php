<?php

namespace App\User\Domain\Data;

use App\Shared\Domain\Data\AbstractMessage;

readonly class UserCreatedMessage extends AbstractMessage
{
    public function __construct(
        private string $firstName
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'first_name' => $this->firstName,
        ];
    }
}