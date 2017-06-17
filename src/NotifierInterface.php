<?php

declare(strict_types=1);

namespace Notify;

interface NotifierInterface
{
    /**
     * @param Recipients $recipients
     * @param NotificationInterface $notification
     *
     * @return void
     */
    public function notify(Recipients $recipients, NotificationInterface $notification);
}
