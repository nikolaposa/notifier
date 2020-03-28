<?php

declare(strict_types=1);

namespace Notifier\Notification;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Recipient\Recipient;

interface EmailNotification extends Notification
{
    public function toEmailMessage(Recipient $recipient): EmailMessage;
}
