<?php

declare(strict_types=1);

namespace Notify\Exception;

use RuntimeException;

class UnhandledChannelException extends RuntimeException implements ExceptionInterface
{
    public static function forChannel(string $channel)
    {
        return new self(sprintf(
            'No message sender has been set for the "%s" channel',
            $channel
        ));
    }
}
