<?php

namespace App\Video\Domain\Enum;

enum VideoStatus: string
{
    case PENDING = 'pending';
    case PROCESSED = 'processed';
}