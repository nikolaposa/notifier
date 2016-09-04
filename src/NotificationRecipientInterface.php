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
interface NotificationRecipientInterface
{
    /**
     * @param NotificationInterface $notification
     * @param string $channel
     *
     * @return bool
     */
    public function acceptsNotification(NotificationInterface $notification, $channel);

    /**
     * @param string $channel
     * @param NotificationInterface $notification
     *
     * @return ContactInterface
     */
    public function getNotifyContact($channel, NotificationInterface $notification);

    /**
     * @param NotificationInterface $notification
     * @param string $channel
     *
     * @return void
     */
    public function onNotified(NotificationInterface $notification, $channel);
}
