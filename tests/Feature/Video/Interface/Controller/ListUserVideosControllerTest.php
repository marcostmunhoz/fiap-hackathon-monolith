<?php

namespace Tests\Feature\Video\Interface\Controller;

use function Pest\Laravel\getJson;
use function Tests\Helpers\Video\createVideoEntity;
use function Tests\Helpers\Video\fakeVideoUserAuthentication;
use function Tests\Helpers\Video\getVideoEntity;
use function Tests\Helpers\Video\getVideoUserAuthenticationHeaders;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->user = getVideoUserEntity();
    fakeVideoUserAuthentication($this->user);
});

test('it returns HTTP 200 with expected response format', function () {
    // Given
    $video = getVideoEntity(userId: $this->user->id);
    createVideoEntity($video);

    // When
    $response = getJson('videos', getVideoUserAuthenticationHeaders());

    // Then
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                ],
            ],
        ]);
});