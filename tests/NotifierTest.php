<?php

declare(strict_types=1);

namespace Notifier\Tests;

use Notifier\Channel\ChannelManager;
use Notifier\Channel\NotificationSender;
use Notifier\Recipient\Recipients;
use Notifier\Tests\TestAsset\Model\Todo;
use Notifier\Tests\TestAsset\Model\TodoExpiredNotification;
use Notifier\Tests\TestAsset\Model\TodoReminderNotification;
use PHPUnit\Framework\TestCase;
use Notifier\Notifier;
use Notifier\Tests\TestAsset\Message\TestNotificationSender;
use Notifier\Tests\TestAsset\Model\User;

class NotifierTest extends TestCase
{
    /** @var Notifier */
    protected $notifier;

    /** @var NotificationSender|TestNotificationSender */
    protected $notificationSender;

    protected function setUp()
    {
        parent::setUp();

        $this->notificationSender = new TestNotificationSender();
        $this->notifier = new Notifier(new ChannelManager([
            'email' => $this->notificationSender,
            'sms' => $this->notificationSender,
        ]));
    }

    /**
     * @test
     */
    public function it_sends_single_notification(): void
    {
        $notification = new TodoReminderNotification(new Todo('Test'));
        $recipients = new Recipients(
            new User('John Doe', [
                'email' => 'john@example.com',
            ])
        );

        $this->notifier->send($notification, $recipients);

        $sentNotifications = $this->notificationSender->getNotifications();

        $this->assertCount(1, $sentNotifications);
    }

    /**
     * @test
     */
    public function it_sends_notification_to_all_supported_channels(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipients = new Recipients(
            new User('John Doe', [
                'email' => 'john@example.com',
            ])
        );

        $this->notifier->send($notification, $recipients);

        $sentNotifications = $this->notificationSender->getNotifications();

        $this->assertCount(2, $sentNotifications);
    }
}
