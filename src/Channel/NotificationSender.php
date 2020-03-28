<?php

declare(strict_types=1);

namespace Notifier\Channel;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

interface NotificationSender
{
    public function send(Notification $notification, Recipient $recipient): void;
}
