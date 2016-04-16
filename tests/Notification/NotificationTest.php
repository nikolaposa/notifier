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
use Notify\Tests\TestAsset\Notification\TestNotification;
use Notify\Tests\TestAsset\Strategy\TestStrategy;
use Notify\Message\MessageInterface;
use Notify\Exception\NotificationStrategyNotSuppliedException;
use Notify\BaseNotification;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotificationTest extends PHPUnit_Framework_TestCase
{
    private $notification;

    protected function setUp()
    {
        parent::setUp();

        $this->notification = new TestNotification();
    }

    public function testMessagesHandledByStrategy()
    {
        $strategy = new TestStrategy();

        $notification = $this->notification;
        $notification($strategy);

        $messages = $strategy->getMessages();

        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(MessageInterface::class, current($messages));
    }

    public function testExceptionIsRaisedIfStrategyNotSupplied()
    {
        $this->expectException(NotificationStrategyNotSuppliedException::class);
        $this->expectExceptionMessage('Strategy for notification "' . TestNotification::class . '" was not supplied');

        $notification = $this->notification;
        $notification();
    }

    public function testMessagesHandledByDefaultStrategy()
    {
        $strategy = new TestStrategy();
        BaseNotification::setDefaultStrategy($strategy);

        $notification = $this->notification;
        $notification();

        $messages = $strategy->getMessages();

        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(MessageInterface::class, current($messages));

        BaseNotification::resetDefaultStrategy();
    }
}
