<?php declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs;

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
    public function createQueueAdapter();

    /**
     * Specification:
     * - Return the client configuration
     *
     * @api
     *
     * @return \ValanticSpryker\Client\Sqs\SqsConfig
     */
    public function getClientConfig();
}
