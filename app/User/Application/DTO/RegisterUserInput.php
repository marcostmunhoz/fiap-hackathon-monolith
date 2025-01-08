<?php

namespace App\User\Application\DTO;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Data;

class RegisterUserInput extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
        #[Email]
        public string $email,
        #[Password(letters: true, mixedCase: true, numbers: true, symbols: true)]
        public string $password,
    ) {
    }
}