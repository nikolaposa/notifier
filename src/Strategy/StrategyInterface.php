<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Strategy;

use Notify\Message\MessageInterface;
use Notify\NotificationInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface StrategyInterface
{
    /**
     * @param MessageInterface[] $messages
     * @param NotificationInterface $notification
     */
    public function handle(array $messages, NotificationInterface $notification = null);
}
