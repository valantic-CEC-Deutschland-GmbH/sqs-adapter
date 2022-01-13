<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use ValanticSpryker\Client\Sqs\SqsClientInterface;
use ValanticSpryker\Zed\Sqs\Business\Model\Queue\Queue;
use ValanticSpryker\Zed\Sqs\Business\Model\Queue\QueueInterface;
use ValanticSpryker\Zed\Sqs\Business\Model\Test\SqsTest;
use ValanticSpryker\Zed\Sqs\Business\Model\Test\SqsTestInterface;
use ValanticSpryker\Zed\Sqs\SqsDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\Sqs\SqsConfig getConfig()
 */
class SqsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\Sqs\Business\Model\Queue\QueueInterface
     */
    public function createQueue(): QueueInterface
    {
        return new Queue(
            $this->getSqsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\Sqs\Business\Model\Test\SqsTestInterface
     */
    public function createSqsTest(): SqsTestInterface
    {
        return new SqsTest(
            $this->getSqsClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\SqsClientInterface
     */
    protected function getSqsClient(): SqsClientInterface
    {
        return $this->getProvidedDependency(SqsDependencyProvider::SQS_CLIENT);
    }
}
