<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sqs\SqsConstants;

class SqsConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getDefaultSqsQueueOptions(): array
    {
        return $this->get(SqsConstants::SQS_CONFIG_QUEUE_OPTIONS, []);
    }
}
