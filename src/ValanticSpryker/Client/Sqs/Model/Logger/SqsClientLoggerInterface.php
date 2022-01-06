<?php declare(strict_types = 1);

namespace Pyz\Client\SqsConnector\Model\Logger;

use Psr\Log\LoggerInterface;

interface SqsClientLoggerInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger);

    /**
     * @param string $message
     *
     * @return void
     */
    public function log(string $message): void;
}
