<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Notification;

use Notify\AbstractNotification;
use Notify\Message\EmailMessage;

class TestNotification extends AbstractNotification
{
    protected function createEmailMessage(array $messageRecipients)
    {
        return new EmailMessage(
            $messageRecipients,
            'Test notification',
            'test notification'
        );
    }
}
