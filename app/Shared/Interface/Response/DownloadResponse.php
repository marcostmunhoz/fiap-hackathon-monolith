<?php

namespace App\Shared\Interface\Response;

use App\Shared\Domain\Data\FileStream;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class DownloadResponse implements Responsable
{
    public function __construct(
        public FileStream $file,
        public int $status = 200
    ) {
    }

    public function toResponse($request): StreamedResponse
    {
        return new StreamedResponse(
            fn () => fpassthru($this->file->stream),
            $this->status,
            [
                'Content-Type' => $this->file->mimeType,
                'Content-Disposition' => "attachment; filename={$this->file->filename}",
            ]
        );
    }
}