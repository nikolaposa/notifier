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
     * @param Recipients $recipients
     *
     * @throws UnsupportedChannelException
     *
     * @return array One or more message objects.
     */
    public function getMessages(string $channel, Recipients $recipients) : array;
}
