<?php declare(strict_types = 1);

namespace Pyz\Client\SqsConnector\Model\Exception;

use InvalidArgumentException;

class QueueNotFoundException extends InvalidArgumentException
{
}
