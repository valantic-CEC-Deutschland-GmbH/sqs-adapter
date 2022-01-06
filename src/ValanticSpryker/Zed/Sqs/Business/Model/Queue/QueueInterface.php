<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business\Model\Queue;

use Psr\Log\LoggerInterface;

interface QueueInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function purgeAllQueues(LoggerInterface $logger);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function createQueues(LoggerInterface $logger);

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function deleteQueues(LoggerInterface $logger);
}
