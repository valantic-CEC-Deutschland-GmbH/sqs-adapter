<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use ValanticSpryker\Client\Sqs\Model\Consumer\ConsumerInterface;
use ValanticSpryker\Client\Sqs\Model\Manager\ManagerInterface;
use ValanticSpryker\Client\Sqs\Model\Publisher\PublisherInterface;

class SqsAdapter implements SqsAdapterInterface
{
    protected ManagerInterface $manager;

    protected ConsumerInterface $consumer;

    protected PublisherInterface $publisher;

    /**
     * @param \ValanticSpryker\Client\Sqs\Model\Manager\ManagerInterface $manager
     * @param \ValanticSpryker\Client\Sqs\Model\Consumer\ConsumerInterface $consumer
     * @param \ValanticSpryker\Client\Sqs\Model\Publisher\PublisherInterface $publisher
     */
    public function __construct(
        ManagerInterface $manager,
        ConsumerInterface $consumer,
        PublisherInterface $publisher
    ) {
        $this->manager = $manager;
        $this->consumer = $consumer;
        $this->publisher = $publisher;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = []): array
    {
        return $this->manager->createQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = []): bool
    {
        return $this->manager->purgeQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = []): bool
    {
        return $this->manager->deleteQueue($queueName, $options);
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
        return $this->consumer->receiveMessages($queueName, $chunkSize, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = []): QueueReceiveMessageTransfer
    {
        return $this->consumer->receiveMessage($queueName, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        $this->consumer->acknowledge($queueReceiveMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        $this->consumer->reject($queueReceiveMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): bool
    {
        return $this->consumer->handleError($queueReceiveMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer): void
    {
        $this->publisher->sendMessage($queueName, $queueSendMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param array<\Generated\Shared\Transfer\QueueSendMessageTransfer> $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers): void
    {
        $this->publisher->sendMessages($queueName, $queueSendMessageTransfers);
    }
}
