<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientBridge;

class SqsDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AWS_SQS = 'CLIENT_AWS_SQS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addAwsSqsClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAwsSqsClient(Container $container): Container
    {
        $container->set(static::CLIENT_AWS_SQS, function (Container $container) {
            return new SqsAdapterToAwsSqsClientBridge($container->getLocator()->awsSqs()->client());
        });

        return $container;
    }
}
