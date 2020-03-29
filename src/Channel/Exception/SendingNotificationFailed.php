<?php

declare(strict_types=1);

namespace Notifier\Channel\Exception;

use RuntimeException;
use Throwable;

final class SendingNotificationFailed extends RuntimeException implements NotifierChannelException
{
    public static function dueTo(Throwable $error, string $channelName): self
    {
        return new self(
            sprintf(
                'Failed to send notification via %s channel',
                $channelName
            ),
            0,
            $error
        );
    }
}
