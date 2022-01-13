<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Manager;

use Generated\Shared\Transfer\SqsCreateQueueArgsTransfer;
use Generated\Shared\Transfer\SqsDeleteQueueArgsTransfer;
use Generated\Shared\Transfer\SqsPurgeQueueArgsTransfer;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;
use ValanticSpryker\Client\Sqs\SqsConfig;

class Manager implements ManagerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const TAG_VALUE_CREATOR_SPRYKER = 'ValanticSpryker';

    /**
     * @var string
     */
    protected const TAG_KEY_CREATOR = 'Creator';

    /**
     * @var string
     */
    protected const TAG_KEY_NAME = 'Name';

    /**
     * @var string
     */
    protected const API_RESPONSE_QUEUE_URL = 'QueueUrl';

    protected SqsAdapterToAwsSqsClientInterface $awsSqsClient;

    protected SqsConfig $sqsConfig;

    protected QueueUrlHelperInterface $queueUrlHelper;

    /**
     * @param \ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\SqsConfig $sqsConfig
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     */
    public function __construct(
        SqsAdapterToAwsSqsClientInterface $awsSqsClient,
        SqsConfig $sqsConfig,
        QueueUrlHelperInterface $queueUrlHelper
    ) {
        $this->awsSqsClient = $awsSqsClient;
        $this->sqsConfig = $sqsConfig;
        $this->queueUrlHelper = $queueUrlHelper;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = []): array
    {
        $sqsCreateQueueArgsTransfer = (new SqsCreateQueueArgsTransfer())
            ->setQueueName($queueName)
            ->setAttributes($options)
            ->setTags([
                static::TAG_KEY_CREATOR => static::TAG_VALUE_CREATOR_SPRYKER,
                static::TAG_KEY_NAME => $queueName,
            ]);

        $result = $this->awsSqsClient
            ->createQueue($sqsCreateQueueArgsTransfer);

        if ($result->hasKey(static::API_RESPONSE_QUEUE_URL)) {
            return $result->toArray();
        }

        return [];
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = []): bool
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $sqsPurgeQueueArgsTransfer = (new SqsPurgeQueueArgsTransfer())
            ->setQueueUrl($queueUrl);

        $this->awsSqsClient
            ->purgeQueue($sqsPurgeQueueArgsTransfer);

        return true;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = []): bool
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $sqsDeleteQueueArgsTransfer = (new SqsDeleteQueueArgsTransfer())
            ->setQueueUrl($queueUrl);

        $this->awsSqsClient
            ->deleteQueue($sqsDeleteQueueArgsTransfer);

        return true;
    }

    /**
     * @param array $queues
     *
     * @return bool
     */
    public function deleteQueues(array $queues): bool
    {
        $success = true;

        foreach ($queues as $queueName) {
            $success = $this->deleteQueue($queueName) && $success;
        }

        return $success;
    }

    /**
     * @param array $queues
     * @param array $options
     *
     * @return bool
     */
    public function createQueues(array $queues, array $options = []): bool
    {
        $success = true;

        foreach ($queues as $queueName) {
            $success = !empty($this->createQueue($queueName, $options)) && $success;
        }

        return $success;
    }

    /**
     * @param array $queues
     *
     * @return bool
     */
    public function purgeQueues(array $queues): bool
    {
        $success = true;

        foreach ($queues as $queueName) {
            $success = $this->purgeQueue($queueName) && $success;
        }

        return $success;
    }
}
