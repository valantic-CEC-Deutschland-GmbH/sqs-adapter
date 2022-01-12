<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sqs\SqsConstants;

class SqsConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    private const RESPONSE_WAIT_TIME_IN_SECONDS_DEFAULT = 0;

    /**
     * @var int
     */
    private const RESPONSE_VISIBILITY_TIMEOUT_DEFAULT = 30;

    /**
     * @return array
     */
    public function getQueues(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string
    {
        return $this->get(SqsConstants::SQS_BASE_URL, '');
    }

    /**
     * @return string|null
     */
    public function getQueuePathPrefix(): ?string
    {
        return $this->get(SqsConstants::SQS_QUEUE_PATH_PREFIX, '');
    }

    /**
     * @return int
     */
    public function getResponseWaitTime(): int
    {
        return $this->get(SqsConstants::RESPONSE_WAIT_TIME_IN_SECONDS, self::RESPONSE_WAIT_TIME_IN_SECONDS_DEFAULT);
    }

    /**
     * @return int
     */
    public function getResponseVisibilityTimeout(): int
    {
        return $this->get(SqsConstants::RESPONSE_VISIBILITY_TIMEOUT, self::RESPONSE_VISIBILITY_TIMEOUT_DEFAULT);
    }
}
