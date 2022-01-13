<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\Sqs\Business\Model\Test;

use Psr\Log\LoggerInterface;

interface SqsTestInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function sendTestMessage(LoggerInterface $logger): bool;
}
