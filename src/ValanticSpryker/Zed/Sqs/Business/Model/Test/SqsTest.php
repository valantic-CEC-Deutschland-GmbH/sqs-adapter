<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business\Model\Test;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Psr\Log\LoggerInterface;
use ValanticSpryker\Client\Sqs\SqsClientInterface;
use ValanticSpryker\Zed\Sqs\SqsConfig;

class SqsTest implements SqsTestInterface
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
    public function sendTestMessage(LoggerInterface $logger): bool
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        if (empty($queues)) {
            $logger->error('No queues defined.');

            return false;
        }

        $queueSendMessageTransfer = (new QueueSendMessageTransfer())
            ->setBody('Test Message!');

        $sqsAdapter->sendMessage($queues[0], $queueSendMessageTransfer);

        $logger->info(sprintf('Test message sent to queue %s', $queues[0]));

        return true;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function receiveMessage(LoggerInterface $logger): bool
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        if (empty($queues)) {
            $logger->error('No queues defined.');

            return false;
        }

        $queueReceiveMessageTransfer = $sqsAdapter->receiveMessage($queues[0]);

        $logger->info(print_r($queueReceiveMessageTransfer->toArray(), true));

        return true;
    }
}
