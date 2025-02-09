<?php

namespace Tests\Unit\Shared\Infrastructure\Service;

use App\Shared\Infrastructure\Config\GoogleConfig;
use App\Shared\Infrastructure\Service\PubSubTopicResolver;
use Google\Cloud\PubSub\Topic;
use Mockery;

it('resolves the pubsub topic from the client using project id and credentials', function () {
    // Given
    $pubSubClientMock = Mockery::mock('overload:Google\Cloud\PubSub\PubSubClient');
    $topic = Mockery::mock(Topic::class);
    $googleConfigMock = Mockery::mock(GoogleConfig::class);
    $googleConfigMock->shouldReceive('getProjectId')
        ->andReturn('project-id');
    $googleConfigMock->shouldReceive('getPubSubServiceAccountKeyPath')
        ->andReturn('service-account-key-path');
    $googleConfigMock->shouldReceive('getPubSubTopicId')
        ->andReturn('topic-id');
    $pubSubClientMock->shouldReceive('__construct')
        ->with([
            'projectId' => 'project-id',
            'credentials' => 'service-account-key-path',
        ]);
    $pubSubClientMock->shouldReceive('topic')
        ->with('topic-id')
        ->andReturn($topic);
    $sut = new PubSubTopicResolver($googleConfigMock);

    // When
    $result = $sut->resolve();

    // Then
    expect($result)->toBe($topic);
});