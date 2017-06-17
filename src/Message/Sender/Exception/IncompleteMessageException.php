<?php

declare(strict_types=1);

namespace Notify\Message\Sender\Exception;

use LogicException;

class IncompleteMessageException extends LogicException implements ExceptionInterface
{
}
