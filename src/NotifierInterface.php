<?php

declare(strict_types=1);

namespace Notify;

interface NotifierInterface
{
    /**
     * @param array $notificationRecipients
     * @param NotificationInterface $notification
     *
     * @return void
     */
    public function notify(array $notificationRecipients, NotificationInterface $notification);
}
