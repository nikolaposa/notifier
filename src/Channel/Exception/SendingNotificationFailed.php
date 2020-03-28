<?php

declare(strict_types=1);

namespace Notifier\Channel\Exception;

use RuntimeException;
use Throwable;

final class SendingNotificationFailed extends RuntimeException implements NotifierChannelException
{
    public static function dueTo(Throwable $error, string $channel): self
    {
        return new self(
            sprintf(
                'Failed to send notification via %s channel',
                $channel
            ),
            0,
            $error
        );
    }
}
