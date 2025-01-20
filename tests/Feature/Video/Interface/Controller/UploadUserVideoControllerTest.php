<?php

namespace Tests\Feature\Video\Interface\Controller;

use App\Video\Domain\Entity\VideoUserEntity;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\instance;
use function Pest\Laravel\post;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->user = getVideoUserEntity();
    instance(VideoUserEntity::class, $this->user);
});

test('it returns HTTP 201 on successful upload', function () {
    // Given
    $file = UploadedFile::fake()->create('video.mp4', 5 * 1024 * 1024);

    // When
    $response = post('videos', compact('file'));

    // Then
    $response->assertStatus(201);
});