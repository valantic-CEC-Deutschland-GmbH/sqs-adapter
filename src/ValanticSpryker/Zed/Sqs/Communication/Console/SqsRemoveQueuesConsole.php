<?php declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \ValanticSpryker\Zed\Sqs\Business\SqsFacadeInterface getFacade()
 */
class SqsRemoveQueuesConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'sqs:remove-queues';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command removes all configured queues from SQS';

    /**
     * @var string
     */
    public const HELP = 'This command removes all queues configured in SQS';

    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Error removing queues';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGE = 'Queues successfully removed';

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
        $success = $this->getFacade()
            ->deleteQueues($this->getMessenger());

        if ($success === false) {
            $this->error(static::ERROR_MESSAGE);

            return static::CODE_ERROR;
        }

        $this->success(static::SUCCESS_MESSAGE);

        return static::CODE_SUCCESS;
    }
}
