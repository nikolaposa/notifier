<?php

declare(strict_types=1);

namespace Notifier\Tests\Notification;

use Notifier\Channel\Email\EmailChannel;
use Notifier\Channel\Sms\SmsChannel;
use Notifier\Recipient\Recipient;
use Notifier\Tests\TestAsset\Model\Todo;
use Notifier\Tests\TestAsset\Model\TodoExpiredNotification;
use Notifier\Tests\TestAsset\Model\User;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /** @var TodoExpiredNotification */
    protected $notification;

    /** @var Recipient */
    protected $recipient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notification = new TodoExpiredNotification(new Todo('Test'));
        $this->recipient = new User('John Doe', [
            EmailChannel::NAME => 'john@example.com',
            SmsChannel::NAME => '+123456789',
        ]);
    }

    /**
     * @test
     */
    public function it_builds_channel_specific_message(): void
    {
        $emailMessage = $this->notification->toEmailMessage($this->recipient);

        $this->assertSame('Todo: <strong>Test</strong> has expired', $emailMessage->body);
    }
}
