<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\Sqs\Business\SqsBusinessFactory getFactory()
 */
class SqsFacade extends AbstractFacade implements SqsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function purgeAllQueues(LoggerInterface $logger)
    {
        return $this->getFactory()
            ->createQueue()
            ->purgeAllQueues($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function createQueues(LoggerInterface $logger)
    {
        return $this->getFactory()
            ->createQueue()
            ->createQueues($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function deleteQueues(LoggerInterface $logger)
    {
        return $this->getFactory()
            ->createQueue()
            ->deleteQueues($logger);
    }
}
