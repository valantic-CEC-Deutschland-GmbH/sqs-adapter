<?php

declare(strict_types = 1 );

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

/**
 * @method \ValanticSpryker\Client\Sqs\SqsFactory getFactory()
 */
class SqsClient extends AbstractClient implements SqsClientInterface
{
    /**
     * @inheritDoc
     *
     * @api
     *
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    public function createQueueAdapter(): AdapterInterface
    {
        return $this->getFactory()
            ->createQueueAdapter();
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @return \ValanticSpryker\Client\Sqs\SqsConfig
     */
    public function getClientConfig(): SqsConfig
    {
        return $this->getFactory()
            ->getConfig();
    }
}
