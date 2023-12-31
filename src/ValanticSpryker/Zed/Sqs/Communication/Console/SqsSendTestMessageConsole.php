<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \ValanticSpryker\Zed\Sqs\Business\SqsFacadeInterface getFacade()
 */
class SqsSendTestMessageConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'sqs:send-test-message';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command send a test message to the first queue in SqsConfig';

    /**
     * @var string
     */
    public const HELP = 'This command send a test message to the first queue in SqsConfig';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->setHelp(static::HELP);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()
            ->sendTestMessage($this->getMessenger());

        $output->writeln('Test message sent.');

        $queueReceiveMessageTransfer = $this->getFacade()
            ->receiveMessage($this->getMessenger());

        $output->writeln('Message received.');
        $output->writeln(sprintf('Message body: %s', $queueReceiveMessageTransfer->getQueueMessage()->getBody()));

        return static::CODE_SUCCESS;
    }
}
