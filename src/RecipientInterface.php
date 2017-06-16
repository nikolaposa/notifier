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

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface RecipientInterface
{
    /**
     * @return string
     */
    public function getRecipientName() : string;

    /**
     * @param string $channel
     * @param NotificationInterface $notification
     *
     * @return string
     */
    public function getRecipientContact(string $channel, NotificationInterface $notification) : string;

    /**
     * @param NotificationInterface $notification
     * @param string $channel
     *
     * @return bool
     */
    public function acceptsNotification(NotificationInterface $notification, string $channel) : bool;

    /**
     * @param NotificationInterface $notification
     * @param string $channel
     *
     * @return void
     */
    public function onNotified(NotificationInterface $notification, string $channel);
}
