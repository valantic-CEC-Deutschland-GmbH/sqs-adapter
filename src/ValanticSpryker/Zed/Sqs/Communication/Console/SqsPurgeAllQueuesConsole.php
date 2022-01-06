<?php declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \ValanticSpryker\Zed\Sqs\Business\SqsFacadeInterface getFacade()
 */
class SqsPurgeAllQueuesConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'sqs:purge-queues';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command removes all messages from configured queues in SQS';

    /**
     * @var string
     */
    public const HELP = 'This command removes all messages from all queues';

    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Error on purging queues';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGE = 'Queues successfully purged';

    /**
     * @return void
     */
    protected function configure()
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
        $success = $this->getFacade()
            ->purgeAllQueues($this->getMessenger());

        if ($success === false) {
            $this->error(static::ERROR_MESSAGE);

            return static::CODE_ERROR;
        }

        $this->success(static::SUCCESS_MESSAGE);

        return static::CODE_SUCCESS;
    }
}
