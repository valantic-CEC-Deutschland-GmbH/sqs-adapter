<?php declare(strict_types = 1);

namespace ValanticSpryker\Shared\Sqs;

interface SqsConstants
{
    /**
     * @var string
     */
    public const RESPONSE_WAIT_TIME_IN_SECONDS = 'RESPONSE_WAIT_TIME_IN_SECONDS';

    /**
     * @var string
     */
    public const RESPONSE_VISIBILITY_TIMEOUT = 'RESPONSE_VISIBILITY_TIMEOUT';

    /**
     * @var string
     */
    public const SQS_BASE_URL = 'SQS_BASE_URL';

    /**
     * @var string
     */
    public const SQS_QUEUE_PATH_PREFIX = 'SQS_QUEUE_PATH_PREFIX';

    /**
     * @var string
     */
    public const SQS_CONFIG_QUEUE_OPTIONS = 'SQS_CONFIG_QUEUE_OPTIONS';
}
