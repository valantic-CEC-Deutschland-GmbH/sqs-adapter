<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use ValanticSpryker\Zed\Sqs\Business\Model\Queue\Queue;
use ValanticSpryker\Zed\Sqs\SqsDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\Sqs\SqsConfig getConfig()
 */
class SqsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\Sqs\Business\Model\Queue\QueueInterface
     */
    public function createQueue()
    {
        return new Queue(
            $this->getSqsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\SqsClientInterface
     */
    protected function getSqsClient()
    {
        return $this->getProvidedDependency(SqsDependencyProvider::SQS_CLIENT);
    }
}
