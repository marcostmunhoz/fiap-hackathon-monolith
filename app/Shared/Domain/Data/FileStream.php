<?php

namespace App\Shared\Domain\Data;

use InvalidArgumentException;

readonly class FileStream
{
    private const string RESOURCE_TYPE_STREAM = 'stream';

    /**
     * @param resource $stream
     */
    public function __construct(
        public mixed $stream,
        public string $filename,
        public string $mimeType
    ) {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('The stream must be a resource.');
        }

        // @codeCoverageIgnoreStart
        if (get_resource_type($stream) !== self::RESOURCE_TYPE_STREAM) {
            throw new InvalidArgumentException('The stream must be a stream resource.');
        }
        // @codeCoverageIgnoreEnd
    }

    public function __destruct()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }
}