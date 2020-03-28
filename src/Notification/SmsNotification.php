<?php

declare(strict_types=1);

namespace Notifier\Notification;

use Notifier\Channel\Sms\SmsMessage;
use Notifier\Recipient\Recipient;

interface SmsNotification extends Notification
{
    public function toSmsMessage(Recipient $recipient): SmsMessage;
}
