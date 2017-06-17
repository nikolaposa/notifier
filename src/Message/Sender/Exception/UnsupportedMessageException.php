<?php

declare(strict_types=1);

namespace Notify\Message\Sender\Exception;

use LogicException;
use Notify\Message\Sender\MessageSenderInterface;

class UnsupportedMessageException extends LogicException implements ExceptionInterface
{
    public static function fromMessageSenderAndMessage(MessageSenderInterface $messageSender, $message)
    {
        return new self(sprintf(
            '%s does not support %s messages',
            get_class($messageSender),
            get_class($message)
        ));
    }
}
