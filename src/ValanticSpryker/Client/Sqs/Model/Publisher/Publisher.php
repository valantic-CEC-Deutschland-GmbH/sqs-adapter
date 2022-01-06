<?php
declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Publisher;

use Aws\Sqs\SqsClient;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;

class Publisher implements PublisherInterface
{
    use LoggerTrait;

    private SqsClient $awsSqsClient;

    private QueueUrlHelperInterface $queueUrlHelper;

    private int $batchSize = 10;

    /**
     * @param \Aws\Sqs\SqsClient $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     */
    public function __construct(
        SqsClient $awsSqsClient,
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
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        $message = $this->createMessage($queueName, $queueSendMessageTransfer);

        $this->awsSqsClient
            ->sendMessageAsync($message);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer[] $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        $sendBatch = [];

        foreach ($queueSendMessageTransfers as $queueSendMessageTransfer) {
            $sendBatch[] = $queueSendMessageTransfer;

            if (count($sendBatch) >= $this->batchSize) {
                $messages = $this->createMessageBatch($queueName, $sendBatch);

                $this->awsSqsClient
                    ->sendMessageBatchAsync($messages);

                $sendBatch = [];
            }
        }

        if (!empty($sendBatch)) {
            $messages = $this->createMessageBatch($queueName, $sendBatch);

            $this->awsSqsClient
                ->sendMessageBatchAsync($messages);
        }
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return array
     */
    protected function createMessage(string $queueName, QueueSendMessageTransfer $queueSendMessageTransfer): array
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        return [
            'MessageBody' => $queueSendMessageTransfer->getBody(),
            'QueueUrl' => $queueUrl,
        ];
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer[] $sendBatch
     *
     * @return array
     */
    protected function createMessageBatch(string $queueName, array $sendBatch)
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $entries = [];

        foreach ($sendBatch as $queueSendMessageTransfer) {
            $entries[] = [
                'MessageBody' => $queueSendMessageTransfer->getBody(),
            ];
        }

        return [
            'Entries' => $entries,
            'QueueUrl' => $queueUrl,
        ];
    }
}
