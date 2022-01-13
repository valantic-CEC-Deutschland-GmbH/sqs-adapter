<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class SqsDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SQS_CLIENT = 'SQS_CLIENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSqsClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSqsClient(Container $container): Container
    {
        $container->set(static::SQS_CLIENT, function () use ($container) {
            return $container->getLocator()->sqs()->client();
        });

        return $container;
    }
}
