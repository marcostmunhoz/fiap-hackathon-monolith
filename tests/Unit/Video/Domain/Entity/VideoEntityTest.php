<?php

namespace Tests\Unit\Video\Domain\Entity;

use function Tests\Helpers\Video\getVideoEntity;

test('toArray returns the entity as an array', function () {
    // Given
    $video = getVideoEntity();

    // When
    $result = $video->toArray();

    // Then
    expect($result)->toBe([
        'id' => (string) $video->id,
        'filename' => $video->filename,
        'output_filename' => $video->outputFilename,
        'status' => $video->status->value,
        'user_id' => (string) $video->userId,
        'created_at' => (string) $video->createdAt,
        'updated_at' => (string) $video->updatedAt,
    ]);
});

test('fromArray creates a VideoEntity from an array', function () {
    // Given
    $video = getVideoEntity();

    // When
    $result = $video::fromArray($video->toArray());

    // Then
    expect($result)->id->equals($video->id)->toBeTrue()
        ->filename->toBe($video->filename)
        ->outputFilename->toBe($video->outputFilename)
        ->status->toBe($video->status)
        ->userId->equals($video->userId)->toBeTrue()
        ->createdAt->equals($video->createdAt)->toBeTrue()
        ->updatedAt->equals($video->updatedAt)->toBeTrue();
});