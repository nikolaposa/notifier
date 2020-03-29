<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

interface EmailNotification extends Notification
{
    public function toEmailMessage(Recipient $recipient): EmailMessage;
}
