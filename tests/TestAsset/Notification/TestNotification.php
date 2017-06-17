<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Notification;

use Notify\AbstractNotification;
use Notify\Message\EmailMessage;
use Notify\Recipients;

class TestNotification extends AbstractNotification
{
    protected function createEmailMessage(Recipients $messageRecipients)
    {
        return [
            new EmailMessage(
                $messageRecipients,
                'Test notification',
                'test notification'
            )
        ];
    }
}
