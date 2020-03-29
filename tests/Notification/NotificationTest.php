<?php

declare(strict_types=1);

namespace Notifier\Tests\Notification;

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
            'email' => 'john@example.com',
            'sms' => '+123456789',
        ]);
    }

    /**
     * @test
     */
    public function it_provides_supported_channels(): void
    {
        $supportedChannels = $this->notification->getSupportedChannels();

        $this->assertSame(['email', 'sms'], $supportedChannels);
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
