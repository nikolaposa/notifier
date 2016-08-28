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
use Notify\Message\Sender\TestMessageSender;
use Notify\NotificationInterface;
use Notify\GenericNotification;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\GenericContact;
use Notify\Strategy\Exception\NotHandlingMessageException;
use Notify\Message\Sender\MessageSenderInterface;
use Notify\Message\Sender\Exception\RuntimeException as MessageSenderException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

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

    public function notifications()
    {
        return [
            [
                new GenericNotification([
                    new DummyMessage(
                        new Recipients([
                            new Actor(new GenericContact('test'))
                        ]),
                        'test1'
                    ),
                    new DummyMessage(
                        new Recipients([
                            new Actor(new GenericContact('test'))
                        ]),
                        'test2'
                    ),
                ])
            ],
            [
                new GenericNotification([
                    new DummyMessage(
                        new Recipients([
                            new Actor(new GenericContact('test'))
                        ]),
                        'test3'
                    ),
                ])
            ],
        ];
    }

    /**
     * @dataProvider notifications
     */
    public function testSendingNotificationMessages(NotificationInterface $notification)
    {
        $strategy = new SendStrategy([
            DummyMessage::class => $this->messageSender,
        ]);

        $strategy->handle($notification);

        $sentMessages = $this->messageSender->getMessages();
        $this->assertNotEmpty($sentMessages);
        $this->assertCount(count($notification->getMessages()), $sentMessages);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(NotHandlingMessageException::class);

        $notification = new GenericNotification([
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test test test'
            )
        ]);

        $strategy = new SendStrategy([
            EmailMessage::class => $this->messageSender,
        ]);
        $strategy->handle($notification);
    }

    public function testLoggingMessageSendFailures()
    {
        $notification = new GenericNotification([
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test test test'
            )
        ]);

        $messageSender = $this->getMock(MessageSenderInterface::class);
        $messageSender->expects($this->once())
            ->method('send')
            ->willThrowException(new MessageSenderException('send failed'));

        $strategy = new SendStrategy([
            DummyMessage::class => $messageSender,
        ]);

        $logger = $this->getMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('log')
            ->with($this->equalTo(LogLevel::ERROR));

        $strategy->setLogger($logger);

        $strategy->handle($notification);
    }

    public function testLoggingMessageSendSuccess()
    {
        $notification = new GenericNotification([
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test1'
            ),
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test2'
            ),
        ]);

        $strategy = new SendStrategy([
            DummyMessage::class => $this->messageSender,
        ]);

        $logger = $this->getMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('log')
            ->with($this->equalTo(LogLevel::INFO));

        $strategy->setLogger($logger);

        $strategy->handle($notification);
    }
}
