<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\Sqs\Model;

use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use ValanticSpryker\Client\Sqs\Model\Manager\ManagerInterface;

interface SqsAdapterInterface extends AdapterInterface, ManagerInterface
{
}
