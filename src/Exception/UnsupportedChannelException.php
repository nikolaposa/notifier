<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Exception;

use RuntimeException;
use Notify\NotificationInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class UnsupportedChannelException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param NotificationInterface $notification
     * @param string $channel
     * @return self
     */
    public static function forNotificationAndChannel(NotificationInterface $notification, $channel)
    {
        return new self(sprintf(
            'Notification "%s" cannot be sent through "%s" channel',
            $notification->getName(),
            $channel
        ));
    }
}
