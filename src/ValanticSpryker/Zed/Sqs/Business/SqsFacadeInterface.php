<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
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
    public function purgeAllQueues(LoggerInterface $logger): bool;

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
    public function createQueues(LoggerInterface $logger): bool;

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
    public function deleteQueues(LoggerInterface $logger): bool;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function sendTestMessage(LoggerInterface $logger): bool;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage(LoggerInterface $logger): QueueReceiveMessageTransfer;
}
