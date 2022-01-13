<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Helper;

use ValanticSpryker\Client\Sqs\SqsConfig;

class QueueUrlHelper implements QueueUrlHelperInterface
{
    private SqsConfig $config;

    /**
     * @param \ValanticSpryker\Client\Sqs\SqsConfig $config
     */
    public function __construct(SqsConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function buildQueueUrl(string $queueName): string
    {
        return sprintf(
            '%s/%s%s',
            $this->config->getBaseUrl(),
            $this->config->getQueuePathPrefix(),
            $queueName,
        );
    }
}
