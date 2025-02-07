<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Data\AbstractMessage;
use App\Shared\Domain\Service\MessageProducerInterface;
use Illuminate\Support\Facades\DB;

/**
 * @codeCoverageIgnore
 */
class DatabaseMessageProducer implements MessageProducerInterface
{
    public function send(AbstractMessage $message): void
    {
        DB::table('messages')->insert([
            'data' => json_encode($message, JSON_THROW_ON_ERROR),
        ]);
    }
}