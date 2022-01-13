<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Publisher;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SqsSendMessageArgsTransfer;
use Generated\Shared\Transfer\SqsSendMessageBatchArgsTransfer;
use Generated\Shared\Transfer\SqsSendMessageBatchEntryTransfer;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;

class Publisher implements PublisherInterface
{
    use LoggerTrait;

    private SqsAdapterToAwsSqsClientInterface $awsSqsClient;

    private QueueUrlHelperInterface $queueUrlHelper;

    private int $batchSize = 10;

    /**
     * @param \ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     */
    public function __construct(
        SqsAdapterToAwsSqsClientInterface $awsSqsClient,
        QueueUrlHelperInterface $queueUrlHelper
    ) {
        $this->awsSqsClient = $awsSqsClient;
        $this->queueUrlHelper = $queueUrlHelper;
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer): void
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $sqsSendMessageArgsTransfer = (new SqsSendMessageArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setMessageBody($queueSendMessageTransfer->getBody());

        $this->awsSqsClient
            ->sendMessageAsync($sqsSendMessageArgsTransfer);
    }

    /**
     * @param string $queueName
     * @param array<\Generated\Shared\Transfer\QueueSendMessageTransfer> $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers): void
    {
        $sendBatch = [];

        foreach ($queueSendMessageTransfers as $queueSendMessageTransfer) {
            $sendBatch[] = $queueSendMessageTransfer;

            if (count($sendBatch) >= $this->batchSize) {
                $sqsSendMessageBatchArgsTransfer = $this->createMessageBatch($queueName, $sendBatch);

                $this->awsSqsClient
                    ->sendMessageBatchAsync($sqsSendMessageBatchArgsTransfer);

                $sendBatch = [];
            }
        }

        if (!empty($sendBatch)) {
            $sqsSendMessageBatchArgsTransfer = $this->createMessageBatch($queueName, $sendBatch);

            $this->awsSqsClient
                ->sendMessageBatchAsync($sqsSendMessageBatchArgsTransfer);
        }
    }

    /**
     * @param string $queueName
     * @param array<\Generated\Shared\Transfer\QueueSendMessageTransfer> $queueSendMessageTransfers
     *
     * @return \Generated\Shared\Transfer\SqsSendMessageBatchArgsTransfer
     */
    protected function createMessageBatch(string $queueName, array $queueSendMessageTransfers): SqsSendMessageBatchArgsTransfer
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $sqsSendMessageBatchArgsTransfer = (new SqsSendMessageBatchArgsTransfer())
            ->setQueueUrl($queueUrl);

        foreach ($queueSendMessageTransfers as $queueSendMessageTransfer) {
            $sqsSendMessageBatchEntryTransfer = (new SqsSendMessageBatchEntryTransfer())
                ->setMessageBody($queueSendMessageTransfer->getBody())
                ->setId($queueSendMessageTransfer->getRoutingKey());

            $sqsSendMessageBatchArgsTransfer->addEntry($sqsSendMessageBatchEntryTransfer);
        }

        return $sqsSendMessageBatchArgsTransfer;
    }
}
