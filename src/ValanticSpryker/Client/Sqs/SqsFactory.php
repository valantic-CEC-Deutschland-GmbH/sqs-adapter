<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialsInterface;
use Aws\Sqs\SqsClient;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use Spryker\Shared\Log\LoggerTrait;
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
     * @var string
     */
    public const AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS = 'credentials';

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
            $this->createAwsSqsClient(),
            $this->getConfig(),
            $this->createQueueUrlHelper()
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Consumer\ConsumerInterface
     */
    public function createSqsConsumer(): ConsumerInterface
    {
        return new Consumer(
            $this->createAwsSqsClient(),
            $this->createQueueUrlHelper(),
            $this->getConfig()
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Publisher\PublisherInterface
     */
    public function createSqsPublisher(): PublisherInterface
    {
        return new Publisher(
            $this->createAwsSqsClient(),
            $this->createQueueUrlHelper()
        );
    }

    /**
     * @return \ValanticSpryker\Client\Sqs\Model\Helper\QueueUrlHelperInterface
     */
    public function createQueueUrlHelper(): QueueUrlHelperInterface
    {
        return new QueueUrlHelper(
            $this->getConfig()
        );
    }

    /**
     * @return \Aws\Sqs\SqsClient
     */
    public function createAwsSqsClient(): SqsClient
    {
        $config = $this->getConfig()
            ->getAwsSqsClientConfig();

        $config[self::AWS_SQS_CLIENT_CONFIG_KEY_CREDENTIALS] = $this->createCredentialsProvider();

        return new SqsClient($config);
    }

    /**
     * @return callable
     */
    public function createCredentialsProvider(): callable
    {
        if ($this->getConfig()->useIamRole()) {
            return CredentialProvider::memoize(CredentialProvider::assumeRoleWithWebIdentityCredentialProvider());
        }

        return CredentialProvider::memoize(CredentialProvider::fromCredentials($this->createCredentials()));
    }

    /**
     * @return \Aws\Credentials\CredentialsInterface
     */
    public function createCredentials(): CredentialsInterface
    {
        return new Credentials(...array_values($this->getConfig()->getCredentials()));
    }
}
