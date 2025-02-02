<?php

namespace Tests\Feature\Video\Interface\Controller;

use Illuminate\Http\UploadedFile;
use function Pest\Laravel\post;
use function Tests\Helpers\Video\fakeVideoUserAuthentication;
use function Tests\Helpers\Video\getVideoUserAuthenticationHeaders;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->user = getVideoUserEntity();
    fakeVideoUserAuthentication($this->user);
});

test('it returns HTTP 201 on successful upload', function () {
    // Given
    $file = UploadedFile::fake()->create('video.mp4', 5 * 1024 * 1024);

    // When
    $response = post('videos', compact('file'), getVideoUserAuthenticationHeaders());

    // Then
    $response->assertStatus(201);
});