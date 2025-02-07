<?php

namespace App\Video\Domain\Data;

use App\Shared\Domain\Data\AbstractMessage;

readonly class VideoUploadedMessage extends AbstractMessage
{
    private const string EVENT = 'video-uploaded';

    public function __construct(
        public string $filename,
        public string $userName,
        public string $userEmail
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'event' => self::EVENT,
            'data' => [
                'filename' => $this->filename,
                'user_name' => $this->userName,
                'user_email' => $this->userEmail,
            ],
        ];
    }
}