<?php

namespace Tests\Unit\Shared\Domain\Data;

use App\Shared\Domain\Data\FileStream;
use InvalidArgumentException;

it('throws when the stream is not a resource', function () {
    // Given
    $stream = 'not a resource';

    // When
    new FileStream($stream, 'filename', 'mimeType');
})->throws(InvalidArgumentException::class, 'The stream must be a resource.');

test('the resource is closed when the object is destroyed', function () {
    // Given
    $stream = fopen('php://temp', 'r+b');
    $fileStream = new FileStream($stream, 'filename', 'mimeType');

    // When
    unset($fileStream);

    // Then
    expect(is_resource($stream))->toBeFalse();
});