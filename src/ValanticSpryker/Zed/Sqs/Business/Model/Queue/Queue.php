<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business\Model\Queue;

use Psr\Log\LoggerInterface;
use ValanticSpryker\Client\Sqs\SqsClientInterface;
use ValanticSpryker\Zed\Sqs\SqsConfig;

class Queue implements QueueInterface
{
    protected SqsClientInterface $sqsClient;

    protected SqsConfig $config;

    /**
     * @param \ValanticSpryker\Client\Sqs\SqsClientInterface $sqsClient
     * @param \ValanticSpryker\Zed\Sqs\SqsConfig $config
     */
    public function __construct(SqsClientInterface $sqsClient, SqsConfig $config)
    {
        $this->sqsClient = $sqsClient;
        $this->config = $config;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function purgeAllQueues(LoggerInterface $logger): bool
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        foreach ($queues as $queue) {
            $sqsAdapter->purgeQueue($queue);
            $logger->info(sprintf('Purge queue "%s" request send.', $queue));
        }

        return true;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function createQueues(LoggerInterface $logger): bool
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        $queueOptions = $this->config
            ->getDefaultSqsQueueOptions();

        foreach ($queues as $queue) {
            if (empty($sqsAdapter->createQueue($queue, $queueOptions))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function deleteQueues(LoggerInterface $logger): bool
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        foreach ($queues as $queue) {
            if ($sqsAdapter->deleteQueue($queue) === false) {
                return false;
            }
        }

        return true;
    }
}
