<?php

declare(strict_types=1);

namespace Notify;

final class Notifier extends AbstractNotifier
{
    public function notify(array $notificationRecipients, NotificationInterface $notification)
    {
        $this->notifyIndividually($notificationRecipients, $notification);
    }
}
