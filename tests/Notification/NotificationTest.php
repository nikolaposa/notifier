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
use Notify\Tests\TestAsset\Notification\NewCommentNotification;
use Notify\Tests\TestAsset\Entity\User;
use Notify\Tests\TestAsset\Entity\Post;
use Notify\Tests\TestAsset\Entity\Comment;
use Notify\Strategy\TestStrategy;
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

        $user = new User('admin', 'John', 'Doe', 'jd@example.com');
        $post = new Post('Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $user);
        $comment = new Comment('Jane', 'jane@example.com', 'Nice article!');
        $post->comment($comment);

        $this->notification = new NewCommentNotification($post, $comment);
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
        $this->setExpectedException(NotificationStrategyNotSuppliedException::class);
        $this->expectExceptionMessage('Strategy for notification "Notify\Tests\TestAsset\Notification\NewCommentNotification" was not supplied');

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
