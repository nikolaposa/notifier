<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Channel;

use Notifier\Channel\NotificationSender;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

final class TestNotificationSender implements NotificationSender
{
    /** @var Notification[] */
    private $notifications = [];

    public function send(Notification $notification, Recipient $recipient): void
    {
        $this->notifications[] = $notification;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
