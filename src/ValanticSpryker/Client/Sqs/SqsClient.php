<?php declare(strict_types = 1 );

namespace ValanticSpryker\Client\Sqs;

use Spryker\Client\Kernel\AbstractClient;

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
    public function createQueueAdapter()
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
    public function getClientConfig()
    {
        return $this->getFactory()
            ->getConfig();
    }
}
