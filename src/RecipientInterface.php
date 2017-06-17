<?php

declare(strict_types=1);

namespace Notify;

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
