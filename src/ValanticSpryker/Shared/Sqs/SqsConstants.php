<?php declare(strict_types = 1);

namespace ValanticSpryker\Shared\Sqs;

interface SqsConstants
{
    /**
     * @var string
     */
    public const MAX_NUMBER_OF_MESSAGES_TO_SENT = 'MAX_NUMBER_OF_MESSAGES_TO_SENT';

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
    public const SPRYKER_AWS_SQS_CLIENT_CONFIG = 'SPRYKER_AWS_SQS_CLIENT_CONFIG';

    /**
     * @var string
     */
    public const SPRYKER_SQS_AWS_ACCOUNT_ID = 'SPRYKER_SQS_AWS_ACCOUNT_ID';

    /**
     * @var string
     */
    public const SQS_CONFIG_PARAM_REGION = 'SQS_CONFIG_PARAM_REGION';

    /**
     * @var string
     */
    public const SQS_CONFIG_PARAM_VERSION = 'SPRYKER_SQS_CONNECTOR_CONFIG_PARAM_VERSION';

    /**
     * @var string
     */
    public const SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_KEY = 'SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_KEY';

    /**
     * @var string
     */
    public const SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_SECRET = 'SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_SECRET';

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
    public const AWS_SQS_CONFIG_QUEUE_OPTIONS = 'AWS_SQS_CONFIG_QUEUE_OPTIONS';

    /**
     * Define whether IAM role should be used. If not, credentials must be passed.
     *
     * @var string
     */
    public const SPRYKER_SQS_CONNECTOR_CONFIG_PARAM_USE_IAM = 'SPRYKER_SQS_CONNECTOR_CONFIG_PARAM_USE_IAM';
}
