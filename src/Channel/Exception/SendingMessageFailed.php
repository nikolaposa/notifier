<?php

declare(strict_types=1);

namespace Notifier\Channel\Exception;

use RuntimeException;
use Throwable;

final class SendingMessageFailed extends RuntimeException implements NotifierChannelException
{
    public static function dueTo(Throwable $error): self
    {
        return new self('Sending of a message has failed: ' . $error->getMessage(), 0, $error);
    }
}
