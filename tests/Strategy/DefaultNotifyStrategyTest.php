<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Notify\Notifier;
use Notify\Tests\TestAsset\Message\TestMessageSender;
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\Tests\TestAsset\Entity\User;
use Notify\Exception\UnhandledChannelException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class DefaultNotifyStrategyTest extends TestCase
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
        $strategy = new Notifier([
            'email' => $this->messageSender,
        ]);

        $strategy->notify([
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

        $strategy = new Notifier([
            'foobar' => $this->messageSender,
        ]);

        $strategy->notify([
            new User([
                'email' => 'test@example.com'
            ]),
        ], new TestNotification());
    }

    public function testNotificationMessageNotSentIfRecipientDoesntAcceptRelatedChannel()
    {
        $strategy = new Notifier([
            'email' => $this->messageSender,
        ]);

        $strategy->notify([
            new User([
                'phone' => '123456'
            ]),
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();
        
        $this->assertEmpty($sentMessages);
    }
}
