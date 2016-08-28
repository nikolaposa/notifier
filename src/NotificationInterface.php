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

use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface NotificationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param type $channelName
     *
     * @return bool
     */
    public function isCapableFor($channelName);

    /**
     * @param string $channelName
     * @param NotificationReceiverInterface $receiver
     *
     * @return MessageInterface
     */
    public function getMessage($channelName, NotificationReceiverInterface $receiver);
}
