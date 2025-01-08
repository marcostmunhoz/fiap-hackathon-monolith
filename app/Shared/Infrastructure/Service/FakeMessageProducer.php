<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\AbstractMessage;
use App\Shared\Domain\Service\MessageProducerInterface;
use Illuminate\Support\Facades\Log;

/**
 * @codeCoverageIgnore
 */
readonly class FakeMessageProducer implements MessageProducerInterface
{
    public function send(AbstractMessage $message): void
    {
        Log::debug('[FakeMessageProducer] Message sent', [
            'type' => $message::class,
            'content' => $message->jsonSerialize(),
        ]);
    }
}