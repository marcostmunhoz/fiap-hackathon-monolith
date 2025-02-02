<?php

namespace Tests\Feature\Video\Interface\Controller;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\getJson;
use function Tests\Helpers\Video\createVideoEntity;
use function Tests\Helpers\Video\fakeVideoUserAuthentication;
use function Tests\Helpers\Video\getVideoEntity;
use function Tests\Helpers\Video\getVideoUserAuthenticationHeaders;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->user = getVideoUserEntity();
    fakeVideoUserAuthentication($this->user);

    Storage::fake('videos');
});

test('it returns the download response for given file', function () {
    // Given
    $file = UploadedFile::fake()->create('video.mp4');
    Storage::disk('videos')->putFile('/', $file);
    $video = getVideoEntity(
        outputFilename: $file->hashName(),
        userId: $this->user->id
    );
    createVideoEntity($video);

    // When
    $response = getJson("videos/{$video->id}/download", getVideoUserAuthenticationHeaders());

    // Then
    $response->assertStatus(200)
        ->assertDownload($video->outputFilename);
});