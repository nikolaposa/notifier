<?php

declare(strict_types=1);

namespace Notify\Exception;

use Notify\NotificationInterface;
use RuntimeException;

class UnsupportedChannelException extends RuntimeException implements ExceptionInterface
{
    public static function forNotificationAndChannel(NotificationInterface $notification, string $channel)
    {
        return new self(sprintf(
            "%s notification cannot be sent through '%s' channel",
            get_class($notification),
            $channel
        ));
    }
}
