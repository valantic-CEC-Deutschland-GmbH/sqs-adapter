<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Helper;

interface QueueUrlHelperInterface
{
    /**
     * @param string $queueName
     *
     * @return string
     */
    public function buildQueueUrl(string $queueName): string;
}
