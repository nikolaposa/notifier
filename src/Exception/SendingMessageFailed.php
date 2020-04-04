<?php

declare(strict_types=1);

namespace Notifier\Exception;

use RuntimeException;
use Throwable;

final class SendingMessageFailed extends RuntimeException implements NotifierException
{
    public static function dueTo(Throwable $error): self
    {
        return new self('Sending of a message has failed: ' . $error->getMessage(), 0, $error);
    }
}
