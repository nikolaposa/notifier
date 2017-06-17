<?php

declare(strict_types=1);

namespace Notify;

use Notify\Exception\UnsupportedChannelException;

interface NotificationInterface
{
    /**
     * @return array
     */
    public function getSupportedChannels() : array;

    /**
     * @param string $channel
     * @param array $recipients
     *
     * @throws UnsupportedChannelException
     *
     * @return object
     */
    public function getMessage(string $channel, array $recipients);
}
