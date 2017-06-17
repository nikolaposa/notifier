<?php

declare(strict_types=1);

namespace Notify;

interface RecipientInterface
{
    /**
     * @param string $channel
     *
     * @return string
     */
    public function getRecipientContact(string $channel) : string;

    /**
     * @return string
     */
    public function getRecipientName() : string;

    /**
     * @param NotificationInterface $notification
     * @param string $channel
     *
     * @return bool
     */
    public function shouldReceive(NotificationInterface $notification, string $channel) : bool;
}
