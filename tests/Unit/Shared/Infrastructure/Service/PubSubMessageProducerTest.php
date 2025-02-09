<?php

namespace Tests\Unit\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\AbstractMessage;
use App\Shared\Infrastructure\Service\PubSubMessageProducer;
use App\Shared\Infrastructure\Service\PubSubTopicResolver;
use Google\Cloud\PubSub\Topic;
use Mockery;

beforeEach(function () {
    $this->resolverMock = Mockery::mock(PubSubTopicResolver::class);
    $this->topicMock = Mockery::mock(Topic::class);
    $this->sut = new PubSubMessageProducer($this->resolverMock);
});

it('publishes the message converted to JSON in the resolved pubsub topic', function () {
    // Given
    $message = new readonly class() extends AbstractMessage {
        public function jsonSerialize(): array
        {
            return ['foo' => 'bar'];
        }
    };
    $this->resolverMock
        ->shouldReceive('resolve')
        ->once()
        ->andReturn($this->topicMock);
    $this->topicMock
        ->shouldReceive('publish')
        ->once()
        ->with(['data' => '{"foo":"bar"}']);

    // When
    $this->sut->send($message);
});
