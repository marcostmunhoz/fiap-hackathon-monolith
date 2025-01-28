<?php

namespace Tests\Feature\Video\Interface\Controller;

use App\Video\Domain\Entity\VideoUserEntity;
use function Pest\Laravel\getJson;
use function Pest\Laravel\instance;
use function Tests\Helpers\Video\createVideoEntity;
use function Tests\Helpers\Video\getVideoEntity;
use function Tests\Helpers\Video\getVideoUserEntity;

beforeEach(function () {
    $this->user = getVideoUserEntity();
    instance(VideoUserEntity::class, $this->user);
});

test('it returns HTTP 200 with expected response format', function () {
    // Given
    $video = getVideoEntity(userId: $this->user->id);
    createVideoEntity($video);

    // When
    $response = getJson('videos');

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