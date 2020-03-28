<?php

declare(strict_types=1);

namespace Notifier\Tests;

use Notifier\Exception\UnsupportedChannelNotifierException;
use Notifier\Notification\Notification;
use Notifier\Tests\TestAsset\Model\User;
use PHPUnit\Framework\TestCase;
use Notifier\Tests\TestAsset\Notification\TestNotification;
use Notifier\Channel\Email\EmailMessage;

class NotificationTest extends TestCase
{
    /**
     * @var Notification
     */
    protected $notification;

    protected function setUp()
    {
        parent::setUp();

        $this->notification = new TestNotification();
    }

    public function testGettingSupportedChannels()
    {
        $supportedChannels = $this->notification->getSupportedChannels();

        $this->assertEquals(['email'], $supportedChannels);
    }

    public function testCreatingMessage()
    {
        $emailMessage = $this->notification->getMessage(
            'email',
            new User([
                'email' => 'test@example.com',
            ])
        );

        $this->assertInstanceOf(EmailMessage::class, $emailMessage);
    }

    public function testCreatingMessageRaisesExceptionIfChannelIsNotSupported()
    {
        try {
            $this->notification->getMessage(
                'sms',
                new User([
                    'email' => 'test@example.com',
                ])
            );

            $this->fail('Exception should have been raised');
        } catch (UnsupportedChannelNotifierException $ex) {
            $this->assertEquals(
                "Notify\\Tests\\TestAsset\\Notification\\TestNotification notification cannot be sent through 'sms' channel",
                $ex->getMessage()
            );
        }
    }
}
