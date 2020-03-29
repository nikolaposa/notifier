<?php

declare(strict_types=1);

namespace Notifier\Channel\Push;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

interface PushNotification extends Notification
{
    public function toPushMessage(Recipient $recipient): PushMessage;
}
