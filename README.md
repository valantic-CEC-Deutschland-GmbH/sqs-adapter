# SQS Adapter

SQS implementation as an adapter for spryker/queue

### Install package
```
composer req valantic-cec/sqs
```

### Update shared config
`config/Shared/config_default.php`

```
$config[KernelConstants::CORE_NAMESPACES] = [
    ...
    'ValanticSpryker',
];
```

### Update your shared config for SQS (sample for ElasticMQ)
```
$config[SqsConstants::SQS_BASE_URL] = 'http://elasticmq:9324';
$config[SqsConstants::SQS_QUEUE_PATH_PREFIX] = 'queue/';
```

### Update your QueueConfig
`\Pyz\Zed\Queue\QueueConfig`

```
...

/**
 * @var string
 */
public const AWS_SQS = 'aws-sqs';

...

/**
 * @return array
 */
protected function getMessageCheckOptions(): array
{
    return [
        QueueConstants::QUEUE_WORKER_MESSAGE_CHECK_OPTION => [
        ...
        static::AWS_SQS => $this->getAwsSqsQueueMessageCheckOptions(),
    ];
}

...

/**
 * @return \Generated\Shared\Transfer\AwsSqsConsumerOptionTransfer
 */
protected function getAwsSqsQueueMessageCheckOptions(): AwsSqsConsumerOptionTransfer
{
    $queueOptionTransfer = new AwsSqsConsumerOptionTransfer();
    $queueOptionTransfer->setCheckMessageCount(true);

    return $queueOptionTransfer;
}

...
```

### Update your ConsoleDependencyProvider

`\Pyz\Zed\Console\ConsoleDependencyProvider`

```
/**
 * @param \Spryker\Zed\Kernel\Container $container
 * 
 * @return \Symfony\Component\Console\Command\Command[]
 */
protected function getConsoleCommands(Container $container): array
{
    $commands = [
        ...
        new SqsCreateQueuesConsole(),
        new SqsPurgeAllQueuesConsole(),
        new SqsRemoveQueuesConsole(),
    ];
```

In many cases when you operate SQS next to RabbitMq, it will make sense to also extend the **Queue** module to separate RabbitMq workers from SQS workers. To achieve this you should extend **QueueDependencyProvider** and **QueueBusinessFactory** to create **Task** and **Worker** for RabbitMq and SQS independently.
