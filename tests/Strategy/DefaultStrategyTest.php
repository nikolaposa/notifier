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

use PHPUnit_Framework_TestCase;
use Notify\Strategy\DefaultStrategy;
use Notify\Strategy\ChannelHandler;
use Notify\Message\Sender\TestMessageSender;
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\Tests\TestAsset\Entity\User;
use Notify\Contact\Contacts;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Exception\UnhandledChannelException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class DefaultStrategyTest extends PHPUnit_Framework_TestCase
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
        $strategy = new DefaultStrategy([
            new ChannelHandler('Email', $this->messageSender),
        ]);

        $strategy->notify([
            new User(new Contacts([
                new EmailContact('test@example.com')
            ])),
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();

        $this->assertNotEmpty($sentMessages);
    }

    public function testExceptionIsRaisedInCaseOfUnhandledChannel()
    {
        $this->expectException(UnhandledChannelException::class);

        $strategy = new DefaultStrategy([
            new ChannelHandler('Foobar', $this->messageSender),
        ]);

        $strategy->notify([
            new User(new Contacts([
                new EmailContact('test@example.com')
            ])),
        ], new TestNotification());
    }

    public function testNotificationMessageNotSentIfRecipientDoesntAcceptRelatedChannel()
    {
        $strategy = new DefaultStrategy([
            new ChannelHandler('Email', $this->messageSender),
        ]);

        $strategy->notify([
            new User(new Contacts([
                new PhoneContact('123456')
            ])),
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();

        $this->assertEmpty($sentMessages);
    }
}
