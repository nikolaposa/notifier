<?php

declare(strict_types=1);

namespace Notifier\Notification;

use Notifier\Channel\Push\PushMessage;
use Notifier\Recipient\Recipient;

interface PushNotification extends Notification
{
    public function toPushMessage(Recipient $recipient): PushMessage;
}
