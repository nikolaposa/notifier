<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use Notifier\Exception\SendingMessageFailed;
use Notifier\Exception\SendingNotificationFailed;
use Notifier\Channel\Sms\SmsChannel;
use Notifier\Notification\Notification;
use Notifier\Tests\TestAsset\Channel\FakeTexter;
use Notifier\Tests\TestAsset\Model\Todo;
use Notifier\Tests\TestAsset\Model\TodoExpiredNotification;
use Notifier\Tests\TestAsset\Model\User;
use PHPUnit\Framework\TestCase;

class SmsChannelTest extends TestCase
{
    /** @var SmsChannel */
    protected $channel;

    /** @var FakeTexter */
    protected $texter;

    protected function setUp(): void
    {
        $this->texter = new FakeTexter();
        $this->channel = new SmsChannel($this->texter, '+111');
    }

    /**
     * @test
     */
    public function it_provides_name(): void
    {
        $this->assertSame(SmsChannel::NAME, $this->channel->getName());
    }

    /**
     * @test
     */
    public function it_sends_notification_via_texter(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            SmsChannel::NAME => '+123456789',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertCount(1, $this->texter->getMessages());
    }

    /**
     * @test
     */
    public function it_does_not_send_if_notification_does_not_support_email_channel(): void
    {
        $notification = new class implements Notification {
        };
        $recipient = new User('John Doe', [
            SmsChannel::NAME => '+123456789',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertCount(0, $this->texter->getMessages());
    }

    /**
     * @test
     */
    public function it_does_not_send_notification_if_recipient_does_not_prefer_sms(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            'email' => 'john@example.com',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertCount(0, $this->texter->getMessages());
    }

    /**
     * @test
     */
    public function it_raises_exception_if_sending_message_fails(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            SmsChannel::NAME => '',
        ]);

        try {
            $this->channel->send($notification, $recipient);

            $this->fail('Exception should have been raised');
        } catch (SendingNotificationFailed $exception) {
            $this->assertSame('Failed to send TodoExpiredNotification via sms channel', $exception->getMessage());
            $this->assertSame(SmsChannel::NAME, $exception->getChannelName());
            $this->assertSame($notification, $exception->getNotification());
            $this->assertSame($recipient, $exception->getRecipient());
            $this->assertInstanceOf(SendingMessageFailed::class, $exception->getPrevious());
        }
    }

    /**
     * @test
     */
    public function it_sets_default_sender_phone_number_if_from_not_set(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            SmsChannel::NAME => '+123456789',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertSame('+111', $this->texter->getMessages()[0]->getFrom());
    }
}
