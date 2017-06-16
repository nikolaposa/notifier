<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Exception\UnsupportedChannelException;
use Notify\Recipients;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface NotificationInterface
{
    /**
     * @return array
     */
    public function getSupportedChannels();

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
