<?php

namespace App\Video\Application\DTO;

use App\Shared\Domain\Data\FileStream;
use Spatie\LaravelData\Data;

class DownloadUserVideoOutput extends Data
{
    public function __construct(
        public FileStream $stream
    ) {
    }
}