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
use Notify\Strategy\SendStrategy;
use Notify\Channel;
use Notify\Message\Sender\TestMessageSender;
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\GenericNotificationReceiver;
use Notify\Contact\Contacts;
use Notify\Contact\GenericContact;
use Notify\Message\Sender\MessageSenderInterface;
use Notify\Message\Sender\Exception\RuntimeException as MessageSenderException;
use Psr\Log\LoggerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SendStrategyTest extends PHPUnit_Framework_TestCase
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
        $strategy = new SendStrategy([
            new Channel('test', $this->messageSender),
        ]);

        $strategy->notify([
            new GenericNotificationReceiver(new Contacts([
                new GenericContact('test')
            ])),
            new GenericNotificationReceiver(new Contacts([
                new GenericContact('test'),
                new GenericContact('email')
            ]))
        ], new TestNotification());

        $sentMessages = $this->messageSender->getMessages();

        $this->assertNotEmpty($sentMessages);
        $this->assertCount(2, $sentMessages);
    }

    public function testLoggingMessageSendFailures()
    {
        $messageSender = $this->getMock(MessageSenderInterface::class);
        $messageSender->expects($this->once())
            ->method('send')
            ->willThrowException(new MessageSenderException('send failed'));

        $strategy = new SendStrategy([
            new Channel('test', $messageSender)
        ]);

        $logger = $this->getMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error');

        $strategy->setLogger($logger);

        $strategy->notify([
            new GenericNotificationReceiver(new Contacts([
                new GenericContact('test')
            ]))
        ], new TestNotification());
    }

    public function testLoggingMessageSendSuccess()
    {
        $strategy = new SendStrategy([
            new Channel('test', $this->messageSender),
        ]);

        $logger = $this->getMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info');

        $strategy->setLogger($logger);

        $strategy->notify([
            new GenericNotificationReceiver(new Contacts([
                new GenericContact('test')
            ]))
        ], new TestNotification());
    }
}
