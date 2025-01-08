<?php

namespace App\Shared\Domain\Service;

use App\Shared\Domain\Data\AbstractMessage;

interface MessageProducerInterface
{
    public function send(AbstractMessage $message): void;
}