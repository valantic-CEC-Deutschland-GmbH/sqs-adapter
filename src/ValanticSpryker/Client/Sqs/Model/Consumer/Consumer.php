<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Consumer;

use Aws\Sqs\SqsClient;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;
use ValanticSpryker\Client\Sqs\SqsConfig;

class Consumer implements ConsumerInterface
{
    use LoggerTrait;

    private SqsClient $awsSqsClient;

    private QueueUrlHelperInterface $queueUrlHelper;

    private SqsConfig $config;

    /**
     * @param \Aws\Sqs\SqsClient $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     * @param \ValanticSpryker\Client\Sqs\SqsConfig $config
     */
    public function __construct(
        SqsClient $awsSqsClient,
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
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function receiveMessages($queueName, $chunkSize = 10, array $options = [])
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $result = $this->awsSqsClient->receiveMessageAsync([
            'MaxNumberOfMessages' => $chunkSize,
            'MessageAttributeNames' => ['All'],
            'QueueUrl' => $queueUrl,
            'WaitTimeSeconds' => $this->config->getResponseWaitTime(),
            'VisibilityTimeout' => $this->config->getResponseVisibilityTimeout(),
        ]);

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

        $result = $this->awsSqsClient->receiveMessage([
            'MaxNumberOfMessages' => 1,
            'MessageAttributeNames' => ['All'],
            'QueueUrl' => $queueUrl,
            'WaitTimeSeconds' => $this->config->getResponseWaitTime(),
        ]);

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

        $this->awsSqsClient->deleteMessage([
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $queueReceiveMessageTransfer->getRoutingKey(),
        ]);
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

        $this->awsSqsClient->changeMessageVisibilityAsync([
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $queueReceiveMessageTransfer->getRoutingKey(),
            'VisibilityTimeout' => 0,
        ]);
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

        return $this->awsSqsClient->sendMessage([
            'QueueUrl' => $queueReceiveMessageTransfer->getQueueName(),
            'MessageBody' => $queueReceiveMessageTransfer->getQueueMessage()->getBody(),
        ])->hasKey('MessageId');
    }

    /**
     * @param string $queueUrl
     *
     * @return int
     */
    protected function getApproximateMessageCount(string $queueUrl): int
    {
        $result = $this->awsSqsClient->getQueueAttributes(
            [
                'QueueUrl' => $queueUrl,
                'AttributeNames' => ['ApproximateNumberOfMessages'],
            ],
        );

        return (int)($result->get('Attributes')['ApproximateNumberOfMessages']);
    }
}
