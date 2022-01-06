<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model\Manager;

use Aws\Sqs\SqsClient;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;
use ValanticSpryker\Client\Sqs\SqsConfig;

class Manager implements ManagerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const TAG_VALUE_CREATOR_SPRYKER = 'Spryker';

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
    protected const API_REQUEST_INDEX_QUEUE_NAME = 'QueueName';

    /**
     * @var string
     */
    protected const API_REQUEST_INDEX_ATTRIBUTES = 'Attributes';

    /**
     * @var string
     */
    protected const API_REQUEST_INDEX_TAGS = 'tags';

    /**
     * @var string
     */
    protected const API_RESPONSE_QUEUE_URL = 'QueueUrl';

    protected SqsClient $awsSqsClient;

    protected SqsConfig $sqsConfig;

    protected QueueUrlHelperInterface $queueUrlHelper;

    /**
     * @param \Aws\Sqs\SqsClient $awsSqsClient
     * @param \ValanticSpryker\Client\Sqs\SqsConfig $sqsConfig
     * @param \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface $queueUrlHelper
     */
    public function __construct(
        SqsClient $awsSqsClient,
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
    public function createQueue($queueName, array $options = [])
    {
        $result = $this->awsSqsClient->createQueue([
            static::API_REQUEST_INDEX_ATTRIBUTES => $options,
            static::API_REQUEST_INDEX_QUEUE_NAME => $queueName,
            static::API_REQUEST_INDEX_TAGS => [
                static::TAG_KEY_CREATOR => static::TAG_VALUE_CREATOR_SPRYKER,
                static::TAG_KEY_NAME => $queueName,
            ],
        ]);

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
    public function purgeQueue($queueName, array $options = [])
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $this->awsSqsClient->purgeQueue([
            static::API_RESPONSE_QUEUE_URL => $queueUrl,
        ]);

        return true;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = [])
    {
        $queueUrl = $this->queueUrlHelper
            ->buildQueueUrl($queueName);

        $this->awsSqsClient->deleteQueue([
            static::API_RESPONSE_QUEUE_URL => $queueUrl,
        ]);

        return true;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
