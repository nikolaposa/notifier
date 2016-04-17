<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message;

use PHPUnit_Framework_TestCase;
use Notify\Message\AbstractMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\GenericContact;
use Notify\Message\Content\ContentProviderInterface;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class MessageTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingMessageWithStringContent()
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [
            new Recipients([
                new Actor(new GenericContact('test'))
            ]),
            'test test test'
        ]);

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
    }

    public function testCreatingMessageWithContentProvider()
    {
        $contentProvider = $this->getMock(ContentProviderInterface::class);
        $contentProvider
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('test test test');

        $message = $this->getMockForAbstractClass(AbstractMessage::class, [
            new Recipients([
                new Actor(new GenericContact('test'))
            ]),
            $contentProvider
        ]);

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
    }

    public function testExceptionIsRaisedInCaseOfInvalidContentArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getMockForAbstractClass(AbstractMessage::class, [
            new Recipients([
                new Actor(new GenericContact('test'))
            ]),
            false
        ]);
    }

    public function testJsonSerializeMessage()
    {
        $recipients = new Recipients([
            new Actor(new GenericContact('test'))
        ]);

        $message = $this->getMockForAbstractClass(AbstractMessage::class, [
            $recipients,
            'test test test'
        ]);

        $this->assertEquals(['recipients' => $recipients], $message->jsonSerialize());
    }
}
