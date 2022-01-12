<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use Spryker\Shared\Log\LoggerTrait;
use ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface;
use ValanticSpryker\Client\Sqs\Model\Consumer\Consumer;
use ValanticSpryker\Client\Sqs\Model\Consumer\ConsumerInterface;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelper;
use ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface;
use ValanticSpryker\Client\Sqs\Model\Manager\Manager;
use ValanticSpryker\Client\Sqs\Model\Manager\ManagerInterface;
use ValanticSpryker\Client\Sqs\Model\Publisher\Publisher;
use ValanticSpryker\Client\Sqs\Model\Publisher\PublisherInterface;
use ValanticSpryker\Client\Sqs\Model\SqsAdapter;

/**
 * @method \ValanticSpryker\Client\Sqs\SqsConfig getConfig()
 */
class SqsFactory extends AbstractFactory
{
    use LoggerTrait;

    /**
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    public function createQueueAdapter(): AdapterInterface
    {
        return new SqsAdapter(
            $this->createSqsManager(),
            $this->createSqsConsumer(),
            $this->createSqsPublisher(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Manager\ManagerInterface
     */
    public function createSqsManager(): ManagerInterface
    {
        return new Manager(
            $this->getAwsSqsClient(),
            $this->getConfig(),
            $this->createQueueUrlHelper(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Consumer\ConsumerInterface
     */
    public function createSqsConsumer(): ConsumerInterface
    {
        return new Consumer(
            $this->getAwsSqsClient(),
            $this->createQueueUrlHelper(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Publisher\PublisherInterface
     */
    public function createSqsPublisher(): PublisherInterface
    {
        return new Publisher(
            $this->getAwsSqsClient(),
            $this->createQueueUrlHelper(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface
     */
    public function createQueueUrlHelper(): QueueUrlHelperInterface
    {
        return new QueueUrlHelper(
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Dependency\Client\SqsAdapterToAwsSqsClientInterface
     */
    public function getAwsSqsClient(): SqsAdapterToAwsSqsClientInterface
    {
        return $this->getProvidedDependency(SqsDependencyProvider::CLIENT_AWS_SQS);
    }
}
