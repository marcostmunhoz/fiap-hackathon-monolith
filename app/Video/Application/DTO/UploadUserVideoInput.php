<?php

namespace App\Video\Application\DTO;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\File;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\MimeTypes;
use Spatie\LaravelData\Data;

class UploadUserVideoInput extends Data
{
    public function __construct(
        #[File(), MimeTypes('video/mp4'), Max(100 * 1024 * 1024)]
        public UploadedFile $file
    ) {
    }
}