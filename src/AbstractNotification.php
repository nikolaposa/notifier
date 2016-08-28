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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractNotification implements NotificationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMessage($channelName, NotificationReceiverInterface $receiver)
    {
        $messageFactory = $this->getMessageFactory($channelName);

        if (!is_callable($messageFactory)) {

        }

        return call_user_func($messageFactory, $channelName, $receiver);
    }

    /**
     * {@inheritdoc}
     */
    public function isCapableFor($channelName)
    {
        $messageFactory = $this->getMessageFactory($channelName);

        return is_callable($messageFactory);
    }

    private function getMessageFactory($channelName)
    {
        return [$this, 'create' . ucfirst(strtolower($channelName)) . 'Message'];
    }
}
