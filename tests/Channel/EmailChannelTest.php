<?php

declare(strict_types=1);

namespace Notifier\Tests\Channel;

use Notifier\Channel\Email\EmailChannel;
use Notifier\Channel\Exception\SendingMessageFailed;
use Notifier\Channel\Exception\SendingNotificationFailed;
use Notifier\Tests\TestAsset\Channel\FakeMailer;
use Notifier\Tests\TestAsset\Model\Todo;
use Notifier\Tests\TestAsset\Model\TodoExpiredNotification;
use Notifier\Tests\TestAsset\Model\User;
use PHPUnit\Framework\TestCase;

class EmailChannelTest extends TestCase
{
    /** @var EmailChannel */
    protected $channel;

    /** @var FakeMailer */
    protected $mailer;

    protected function setUp(): void
    {
        $this->mailer = new FakeMailer();
        $this->channel = new EmailChannel($this->mailer);
    }

    /**
     * @test
     */
    public function it_provides_name(): void
    {
        $this->assertSame(EmailChannel::NAME, $this->channel->getName());
    }

    /**
     * @test
     */
    public function it_sends_notification_via_mailer(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            EmailChannel::NAME => 'john@example.com',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertCount(1, $this->mailer->getMessages());
    }

    /**
     * @test
     */
    public function it_does_not_send_notification_if_recipient_does_not_prefer_email(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            'mobile' => '+123456',
        ]);

        $this->channel->send($notification, $recipient);

        $this->assertCount(0, $this->mailer->getMessages());
    }

    /**
     * @test
     */
    public function it_raises_exception_if_sending_message_fails(): void
    {
        $notification = new TodoExpiredNotification(new Todo('Test'));
        $recipient = new User('John Doe', [
            EmailChannel::NAME => 'invalid',
        ]);

        try {
            $this->channel->send($notification, $recipient);

            $this->fail('Exception should have been raised');
        } catch (SendingNotificationFailed $exception) {
            $this->assertSame('Failed to send TodoExpiredNotification via email channel', $exception->getMessage());
            $this->assertSame(EmailChannel::NAME, $exception->getChannelName());
            $this->assertSame($notification, $exception->getNotification());
            $this->assertSame($recipient, $exception->getRecipient());
            $this->assertInstanceOf(SendingMessageFailed::class, $exception->getPrevious());
        }
    }
}
