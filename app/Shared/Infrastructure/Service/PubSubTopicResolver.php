<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Infrastructure\Config\GoogleConfig;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;

class PubSubTopicResolver
{
    public function __construct(
        private readonly GoogleConfig $config
    ) {
    }

    public function resolve(): Topic
    {
        $client = new PubSubClient([
            'projectId' => $this->config->getProjectId(),
            'credentials' => $this->config->getPubSubServiceAccountKeyPath(),
        ]);

        return $client->topic($this->config->getPubSubTopicId());
    }
}