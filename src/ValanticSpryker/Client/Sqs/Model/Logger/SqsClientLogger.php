<?php declare(strict_types = 1);

namespace Pyz\Client\SqsConnector\Model\Logger;

use Psr\Log\LoggerInterface;

class SqsClientLogger implements SqsClientLoggerInterface
{
    private LoggerInterface $logger;

    /**
     * @inheritDoc
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function log(string $message): void
    {
        $this->logger->critical($message);
    }
}
