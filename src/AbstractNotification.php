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
abstract class AbstractNotification implements NotificationInterface
{
    /**
     * @var array
     */
    private $messageFactories;

    public function getSupportedChannels()
    {
        return $this->getMessageFactoryNames();
    }

    public function getMessages(string $channel, Recipients $recipients) : array
    {
        $messageFactory = $this->getMessageFactory($channel);

        return $this->$messageFactory($recipients);
    }

    final protected function getMessageFactoryNames()
    {
        $this->initMessageFactories();

        return array_keys($this->messageFactories);
    }

    final protected function getMessageFactory(string $channel)
    {
        $this->initMessageFactories();

        if (!isset($this->messageFactories[$channel])) {
            throw UnsupportedChannelException::forNotificationAndChannel($this, $channel);
        }

        return $this->messageFactories[$channel];
    }

    final protected function initMessageFactories()
    {
        if (null !== $this->messageFactories) {
            return;
        }

        $this->messageFactories = [];

        foreach (get_class_methods($this) as $methodName) {
            $matches = [];

            if (preg_match('/^create(?P<channel>.+)Message$/', $methodName, $matches)) {
                $channel = strtolower($matches['channel']);
                $this->messageFactories[$channel] = $methodName;
            }
        }
    }
}
