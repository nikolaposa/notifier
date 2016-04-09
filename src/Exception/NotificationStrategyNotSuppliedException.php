<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Exception;

use LogicException;
use Notify\NotificationInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotificationStrategyNotSuppliedException extends LogicException implements ExceptionInterface
{
    public static function forNotification(NotificationInterface $notification)
    {
        return new self(sprintf('Strategy for notification "%s" was not supplied', get_class($notification)));
    }
}
