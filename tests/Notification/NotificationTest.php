<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Notification;

use PHPUnit_Framework_TestCase;
use Notify\GenericNotification;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\GenericContact;
use Notify\Tests\TestAsset\Strategy\TestStrategy;
use Notify\Message\MessageInterface;
use Notify\Exception\NotificationStrategyNotSuppliedException;
use Notify\AbstractNotification;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotificationTest extends PHPUnit_Framework_TestCase
{
    private $notification;

    protected function setUp()
    {
        parent::setUp();

        $this->notification = new GenericNotification([
            new DummyMessage(
                new Recipients([
                    new Actor(new GenericContact('test'))
                ]),
                'test1'
            ),
        ]);
    }

    public function testGettingName()
    {
        $this->assertEquals('Generic', $this->notification->getName());
    }

    public function testGettingMessages()
    {
        $this->assertNotEmpty($this->notification->getMessages());
    }

    public function testMessagesHandledByStrategy()
    {
        $strategy = new TestStrategy();

        $notification = $this->notification;
        $notification($strategy);

        $messages = $strategy->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(MessageInterface::class, current($messages));
        $this->assertSame($notification, $strategy->getNotification());
    }

    public function testExceptionIsRaisedIfStrategyNotSupplied()
    {
        $this->expectException(NotificationStrategyNotSuppliedException::class);
        $this->expectExceptionMessage('Strategy for notification "' . GenericNotification::class . '" was not supplied');

        $notification = $this->notification;
        $notification();
    }

    public function testMessagesHandledByDefaultStrategy()
    {
        $strategy = new TestStrategy();
        AbstractNotification::setDefaultStrategy($strategy);

        $notification = $this->notification;
        $notification();

        $messages = $strategy->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(MessageInterface::class, current($messages));
        $this->assertSame($notification, $strategy->getNotification());

        AbstractNotification::resetDefaultStrategy();
    }
}
