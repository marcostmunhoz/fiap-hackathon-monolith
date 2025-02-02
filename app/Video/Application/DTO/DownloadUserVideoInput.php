<?php

namespace App\Video\Application\DTO;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DownloadUserVideoInput extends Data
{
    public function __construct(
        #[Required]
        public string $id
    ) {
    }
}