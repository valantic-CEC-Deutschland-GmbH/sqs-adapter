<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sqs\SqsConstants;

class SqsConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const AWS_SQS_CONFIG_QUEUE_OPTION_MAX_RECEIVE_COUNT_DEFAULT = 10;

    /**
     * @return array
     */
    public function getDefaultSqsQueueOptions(): array
    {
        return $this->get(SqsConstants::AWS_SQS_CONFIG_QUEUE_OPTIONS, []);
    }
}
