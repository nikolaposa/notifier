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
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractNotification implements NotificationInterface
{
    /**
     * @var array
     */
    private $messageFactories = null;

    /**
     * {@inheritdoc}
     */
    public function getSupportedChannels()
    {
        return $this->getMessageFactoryNames();
    }

    /**
     * {@inheritdoc}
     */
    public function isChannelSupported($channel)
    {
        try {
            $this->getMessageFactory($channel);
        } catch (UnsupportedChannelException $ex) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage($channel, NotificationRecipientInterface $recipient)
    {
        $messageFactory = $this->getMessageFactory($channel);

        $messageRecipients = $this->createMessageRecipients($recipient, $channel);

        return $this->$messageFactory($messageRecipients);
    }

    final protected function getMessageFactoryNames()
    {
        $this->initMessageFactories();

        return array_keys($this->messageFactories);
    }

    final protected function getMessageFactory($channel)
    {
        $this->initMessageFactories();

        if (!isset($this->messageFactories[$channel])) {
            throw UnsupportedChannelException::forNotificationAndChannel($this, $channel);
        }

        return $this->messageFactories[$channel];
    }

    final protected function initMessageFactories()
    {
        if (!is_null($this->messageFactories)) {
            return;
        }

        $this->messageFactories = [];

        foreach (get_class_methods($this) as $methodName) {
            $matches = [];

            if (preg_match('/^create(?P<channel>.+)Message$/', $methodName, $matches)) {
                $channel = $matches['channel'];
                $this->messageFactories[$channel] = $methodName;
            }
        }
    }

    protected function createMessageRecipients(NotificationRecipientInterface $recipient, $channel)
    {
        return new Recipients([
            new Actor($recipient->getNotifyContact($channel, $this)),
        ]);
    }
}
