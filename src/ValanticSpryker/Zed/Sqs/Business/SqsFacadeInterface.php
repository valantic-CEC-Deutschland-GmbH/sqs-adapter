<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business;

use Psr\Log\LoggerInterface;

interface SqsFacadeInterface
{
    /**
     * Specification:
     * - Purges all existing queues.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function purgeAllQueues(LoggerInterface $logger);

    /**
     * Specification:
     * - Create all queues
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function createQueues(LoggerInterface $logger);

    /**
     * Specification:
     * - Delete all queues
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function deleteQueues(LoggerInterface $logger);
}
