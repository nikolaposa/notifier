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
use Notify\Message\SendService\MockSendService;
use Notify\NotificationInterface;
use Notify\GenericNotification;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\GenericContact;
use Notify\Strategy\Exception\NotHandlingMessageException;
use Notify\Message\SendService\SendServiceInterface;
use Notify\Message\SendService\Exception\RuntimeException as SendServiceException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SendStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockSendService
     */
    private $sendService;

    protected function setUp()
    {
        parent::setUp();

        $this->sendService = new MockSendService();
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
            DummyMessage::class => $this->sendService,
        ]);

        $strategy->handle($notification);

        $sentMessages = $this->sendService->getMessages();
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
            EmailMessage::class => $this->sendService,
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

        $sendService = $this->getMock(SendServiceInterface::class);
        $sendService->expects($this->once())
            ->method('send')
            ->willThrowException(new SendServiceException('send failed'));

        $strategy = new SendStrategy([
            DummyMessage::class => $sendService,
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
            DummyMessage::class => $this->sendService,
        ]);

        $logger = $this->getMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('log')
            ->with($this->equalTo(LogLevel::INFO));

        $strategy->setLogger($logger);

        $strategy->handle($notification);
    }
}
