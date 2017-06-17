<?php

declare(strict_types=1);

namespace Notify\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Notify\Notifier;
use Notify\Tests\TestAsset\Message\TestMessageSender;
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\Tests\TestAsset\Entity\User;
use Notify\Exception\UnhandledChannelException;

class NotifierTest extends TestCase
{
    /**
     * @var TestMessageSender
     */
    private $messageSender;

    protected function setUp()
    {
        parent::setUp();

        $this->messageSender = new TestMessageSender();
    }

    public function testSendingNotification()
    {
        $notifier = new Notifier([
            'email' => $this->messageSender,
        ]);

        $notifier->notify([
            new User([
                'email' => 'test@example.com'
            ]),
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();

        $this->assertNotEmpty($sentMessages);
    }

    public function testExceptionIsRaisedInCaseOfUnhandledChannel()
    {
        $this->expectException(UnhandledChannelException::class);

        $notifier = new Notifier([
            'foobar' => $this->messageSender,
        ]);

        $notifier->notify([
            new User([
                'email' => 'test@example.com'
            ]),
        ], new TestNotification());
    }

    public function testNotificationMessageNotSentIfRecipientDoesntAcceptRelatedChannel()
    {
        $notifier = new Notifier([
            'email' => $this->messageSender,
        ]);

        $notifier->notify([
            new User([
                'phone' => '123456'
            ]),
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();
        
        $this->assertEmpty($sentMessages);
    }
}
