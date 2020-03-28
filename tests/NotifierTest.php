<?php

declare(strict_types=1);

namespace Notifier\Tests;

use Notifier\Recipient\Recipients;
use PHPUnit\Framework\TestCase;
use Notifier\Notifier;
use Notifier\Tests\TestAsset\Message\TestNotificationSender;
use Notifier\Tests\TestAsset\Notification\TestNotification;
use Notifier\Tests\TestAsset\Model\User;
use Notifier\Exception\UnhandledChannelNotifierException;

class NotifierTest extends TestCase
{
    /**
     * @var TestNotificationSender
     */
    private $messageSender;

    protected function setUp()
    {
        parent::setUp();

        $this->messageSender = new TestNotificationSender();
    }

    public function testSendingNotification()
    {
        $notifier = new Notifier([
            'email' => $this->messageSender,
        ]);

        $notifier->notify(Recipients::fromArray([
            new User([
                'email' => 'test@example.com'
            ]),
        ]), new TestNotification());

        $sentMessages = $this->messageSender->getMessages();

        $this->assertNotEmpty($sentMessages);
    }

    public function testExceptionIsRaisedInCaseOfUnhandledChannel()
    {
        $this->expectException(UnhandledChannelNotifierException::class);

        $notifier = new Notifier([
            'foobar' => $this->messageSender,
        ]);

        $notifier->notify(Recipients::fromArray([
            new User([
                'email' => 'test@example.com'
            ]),
        ]), new TestNotification());
    }

    public function testNotificationMessageNotSentIfRecipientDoesntAcceptRelatedChannel()
    {
        $notifier = new Notifier([
            'email' => $this->messageSender,
        ]);

        $notifier->notify(Recipients::fromArray([
            new User([
                'phone' => '123456'
            ]),
        ]), new TestNotification());

        $sentMessages = $this->messageSender->getMessages();
        
        $this->assertEmpty($sentMessages);
    }
}
