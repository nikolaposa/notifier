<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

interface SmsNotification extends Notification
{
    public function toSmsMessage(Recipient $recipient): SmsMessage;
}
