<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

interface SqsClientInterface
{
    /**
     * Specification:
     * - Creates an instance of a concrete adapter
     *
     * @api
     *
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    public function createQueueAdapter(): AdapterInterface;

    /**
     * Specification:
     * - Return the client configuration
     *
     * @api
     *
     * @return \ValanticSpryker\Client\Sqs\SqsConfig
     */
    public function getClientConfig(): SqsConfig;
}
