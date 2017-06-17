<?php

declare(strict_types=1);

namespace Notify\Tests;

use Notify\Exception\UnsupportedChannelException;
use Notify\NotificationInterface;
use Notify\Tests\TestAsset\Entity\User;
use PHPUnit\Framework\TestCase;
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\Message\EmailMessage;

class NotificationTest extends TestCase
{
    /**
     * @var NotificationInterface
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
        } catch (UnsupportedChannelException $ex) {
            $this->assertEquals(
                "Notify\\Tests\\TestAsset\\Notification\\TestNotification notification cannot be sent through 'sms' channel",
                $ex->getMessage()
            );
        }
    }
}
