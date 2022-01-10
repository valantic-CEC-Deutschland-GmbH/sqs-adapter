<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sqs\SqsConstants;

class SqsConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_REGION = 'region';

    /**
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_VERSION = 'version';

    /**
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS_KEY = 'key';

    /**
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS_SECRET = 'secret';

    /**
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_ENDPOINT = 'endpoint';

    /**
     * @var string
     */
    public const AWS_SQS_CONFIG_QUEUE_OPTION_DELAY_SECONDS = 'DelaySeconds';

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
     * @return array
     */
    public function getAwsSqsClientConfig(): array
    {
        return $this->get(SqsConstants::SPRYKER_AWS_SQS_CLIENT_CONFIG, $this->getDefaultAwsSqsClientConfig());
    }

    /**
     * @return array
     */
    protected function getDefaultAwsSqsClientConfig(): array
    {
        return [
            self::AWS_SQS_CLIENT_CONFIG_KEY_REGION => $this->getAwsRegion(),
            self::AWS_SQS_CLIENT_CONFIG_KEY_VERSION => $this->getAwsApiVersion(),
            self::AWS_SQS_CLIENT_CONFIG_KEY_ENDPOINT => $this->getBaseUrl(),
        ];
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
     * @return array
     */
    public function getCredentials(): array
    {
        return [
            self::AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS_KEY => $this->get(SqsConstants::SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_KEY, 'default-dummy-key'),
            self::AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS_SECRET => $this->get(SqsConstants::SPRYKER_SQS_CONNECTOR_CONFIG_CREDENTIALS_SECRET, 'default-dummy-secret'),
        ];
    }

    /**
     * @return string
     */
    public function getAwsAccountId(): string
    {
        return $this->get(SqsConstants::SPRYKER_SQS_AWS_ACCOUNT_ID);
    }

    /**
     * @return string
     */
    public function getAwsRegion(): string
    {
        return $this->get(SqsConstants::SQS_CONFIG_PARAM_REGION, 'us-east-1');
    }

    /**
     * @return string
     */
    public function getAwsApiVersion(): string
    {
        return $this->get(SqsConstants::SQS_CONFIG_PARAM_VERSION, 'latest');
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

    /**
     * @return bool
     */
    public function useIamRole(): bool
    {
        return $this->get(SqsConstants::SPRYKER_SQS_CONNECTOR_CONFIG_PARAM_USE_IAM, false);
    }
}
