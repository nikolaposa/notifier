<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Strategy;

use Notify\NotificationInterface;
use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractStrategy implements StrategyInterface
{
    abstract protected function doHandle(array $messages, NotificationInterface $notification);

    /**
     * {@inheritDoc}
     */
    public function handle(NotificationInterface $notification)
    {
        $messages = array_filter($notification->getMessages(), function ($message) {
            /* @var $message MessageInterface */
            return !$message->getRecipients()->isEmpty();
        });

        $this->doHandle($messages, $notification);
    }
}
