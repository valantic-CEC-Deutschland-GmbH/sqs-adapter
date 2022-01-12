<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Consumer;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SqsChangeMessageVisibilityArgsTransfer;
use Generated\Shared\Transfer\SqsDeleteMessageArgsTransfer;
use Generated\Shared\Transfer\SqsGetQueueAttributesArgsTransfer;
use Generated\Shared\Transfer\SqsReceiveMessageArgsTransfer;
use Generated\Shared\Transfer\SqsSendMessageArgsTransfer;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;
use ValanticSpryker\Client\Sqs\SqsConfig;

class Consumer implements ConsumerInterface
{
    use LoggerTrait;

    private SqsAdapterToAwsSqsClientInterface $awsSqsClient;

    private QueueUrlHelperInterface $queueUrlHelper;

    private SqsConfig $config;

    /**
     * @param \ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     * @param \ValanticSpryker\Client\Sqs\SqsConfig $config
     */
    public function __construct(
        SqsAdapterToAwsSqsClientInterface $awsSqsClient,
        QueueUrlHelperInterface $queueUrlHelper,
        SqsConfig $config
    ) {
        $this->awsSqsClient = $awsSqsClient;
        $this->queueUrlHelper = $queueUrlHelper;
        $this->config = $config;
    }

    /**
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function receiveMessages($queueName, $chunkSize = 10, array $options = []): array
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $sqsReceiveMessageArgsTransfer = (new SqsReceiveMessageArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setMaxNumberOfMessages($chunkSize)
            ->setMessageAttributeNames(['All'])
            ->setWaitTimeSeconds($this->config->getResponseWaitTime())
            ->setVisibilityTimeout($this->config->getResponseVisibilityTimeout());

        $result = $this->awsSqsClient
            ->receiveMessage($sqsReceiveMessageArgsTransfer);

        $queueMessages = [];

        foreach ($result->get('Messages') as $message) {
            $queueMessages[] = $this->createQueueReceiveMessageTransfer($queueName, $message);
        }

        return $queueMessages;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = []): QueueReceiveMessageTransfer
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        /** @var \Generated\Shared\Transfer\AwsSqsConsumerOptionTransfer $awsSqsOptions */
        $awsSqsOptions = $options['aws-sqs'];

        if ($awsSqsOptions->getCheckMessageCount()) {
            if ($this->getApproximateMessageCount($queueUrl) === 0) {
                return $this->createQueueReceiveMessageTransfer($queueName, null);
            }

            return $this->createQueueReceiveMessageTransfer($queueName, ['Body' => null, 'ReceiptHandle' => null]);
        }

        $sqsReceiveMessageArgsTransfer = (new SqsReceiveMessageArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setMaxNumberOfMessages(1)
            ->setMessageAttributeNames(['All'])
            ->setWaitTimeSeconds($this->config->getResponseWaitTime())
            ->setVisibilityTimeout($this->config->getResponseVisibilityTimeout());

        $result = $this->awsSqsClient->receiveMessage($sqsReceiveMessageArgsTransfer);

        $messages = $result->get('Messages');

        return $this->createQueueReceiveMessageTransfer($queueName, $messages ? $messages[0] : null);
    }

    /**
     * @param string $queueName
     * @param array|null $message
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function createQueueReceiveMessageTransfer(string $queueName, ?array $message): QueueReceiveMessageTransfer
    {
        $queueMessage = new QueueReceiveMessageTransfer();

        if ($message === null) {
            return $queueMessage;
        }

        $sendMessageTransfer = new QueueSendMessageTransfer();
        $sendMessageTransfer->setBody($message['Body']);
        $sendMessageTransfer->setRoutingKey($message['ReceiptHandle']);

        $queueMessage->fromArray(
            [
                QueueReceiveMessageTransfer::QUEUE_MESSAGE => $sendMessageTransfer,
                QueueReceiveMessageTransfer::ROUTING_KEY => $message['ReceiptHandle'],
                QueueReceiveMessageTransfer::QUEUE_NAME => $queueName,
            ],
        );

        return $queueMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        if ($queueReceiveMessageTransfer->getAcknowledge() === false) {
            return;
        }

        if ($queueReceiveMessageTransfer->getQueueName() === null) {
            return;
        }

        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueReceiveMessageTransfer->getQueueName());

        $sqsDeleteMessageArgsTransfer = (new SqsDeleteMessageArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setReceiptHandle($queueReceiveMessageTransfer->getRoutingKey());

        $this->awsSqsClient->deleteMessage($sqsDeleteMessageArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        if ($queueReceiveMessageTransfer->getQueueName() === null) {
            return;
        }

        if ($queueReceiveMessageTransfer->getQueueMessage()->getRoutingKey() === null) {
            return;
        }

        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueReceiveMessageTransfer->getQueueName());

        $sqsChangeMessageVisibilityArgsTransfer = (new SqsChangeMessageVisibilityArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setReceiptHandle($queueReceiveMessageTransfer->getRoutingKey())
            ->setVisibilityTimeout(0);

        $this->awsSqsClient->changeMessageVisibilityAsync($sqsChangeMessageVisibilityArgsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): bool
    {
        if ($queueReceiveMessageTransfer->getQueueName() === null) {
            return false;
        }

        $sqsSendMessageArgsTransfer = (new SqsSendMessageArgsTransfer())
            ->setQueueUrl($queueReceiveMessageTransfer->getQueueName())
            ->setMessageBody($queueReceiveMessageTransfer->getQueueMessage()->getBody());

        return $this->awsSqsClient
            ->sendMessage($sqsSendMessageArgsTransfer)->hasKey('MessageId');
    }

    /**
     * @param string $queueUrl
     *
     * @return int
     */
    protected function getApproximateMessageCount(string $queueUrl): int
    {
        $sqsGetQueueAttributesArgsTransfer = (new SqsGetQueueAttributesArgsTransfer())
            ->setQueueUrl($queueUrl)
            ->setAttributeNames(['ApproximateNumberOfMessages']);

        $result = $this->awsSqsClient->getQueueAttributes($sqsGetQueueAttributesArgsTransfer);

        return (int)($result->get('Attributes')['ApproximateNumberOfMessages']);
    }
}
