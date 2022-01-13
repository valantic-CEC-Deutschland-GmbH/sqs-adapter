<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business\Model\Test;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Psr\Log\LoggerInterface;
use ValanticSpryker\Client\Sqs\SqsClientInterface;
use ValanticSpryker\Zed\Sqs\Business\Model\Exception\NoQueuesDefinedException;
use ValanticSpryker\Zed\Sqs\SqsConfig;

class SqsTest implements SqsTestInterface
{
    /**
     * @var string
     */
    private const ERROR_NO_QUEUES_DEFINED = 'No queues defined.';

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
            $logger->error(self::ERROR_NO_QUEUES_DEFINED);

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
     * @throws \ValanticSpryker\Zed\Sqs\Business\Model\Exception\NoQueuesDefinedException
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage(LoggerInterface $logger): QueueReceiveMessageTransfer
    {
        $sqsAdapter = $this->sqsClient
            ->createQueueAdapter();

        $queues = $this->sqsClient
            ->getClientConfig()
            ->getQueues();

        if (empty($queues)) {
            $logger->error(self::ERROR_NO_QUEUES_DEFINED);

            throw new NoQueuesDefinedException(self::ERROR_NO_QUEUES_DEFINED);
        }

        return $sqsAdapter->receiveMessage($queues[0]);
    }
}
