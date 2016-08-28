<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Contact\ContactInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface NotificationReceiverInterface
{
    /**
     * @param string $channelName
     * @param NotificationInterface $notification
     *
     * @return bool
     */
    public function shouldReceiveNotification($channelName, NotificationInterface $notification);

    /**
     * @param string $channelName
     * @param NotificationInterface $notification
     *
     * @return ContactInterface
     */
    public function getNotifyContact($channelName, NotificationInterface $notification);
}
