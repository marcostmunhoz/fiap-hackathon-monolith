<?php

namespace App\Video\Application\DTO;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

class ListUserVideosInput extends Data
{
    public function __construct(
        #[Numeric(), Min(1)]
        public int $page = 1,
        #[Numeric(), Min(1), Max(50)]
        public int $perPage = 50,
    ) {
    }
}