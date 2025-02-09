<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\AbstractMessage;
use App\Shared\Domain\Service\MessageProducerInterface;

/**
 * @codeCoverageIgnore
 */
readonly class PubSubMessageProducer implements MessageProducerInterface
{
    public function __construct(
        private PubSubTopicResolver $resolver
    ) {
    }

    public function send(AbstractMessage $message): void
    {
        $topic = $this->resolver->resolve();

        $topic->publish(['data' => json_encode($message, JSON_THROW_ON_ERROR)]);
    }
}