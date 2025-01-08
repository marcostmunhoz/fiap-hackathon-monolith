<?php

namespace App\User\Application\DTO;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class AuthenticateUserInput extends Data
{
    public function __construct(
        #[Email]
        public string $email,
        public string $password,
    ) {
    }
}