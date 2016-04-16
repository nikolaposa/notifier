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
use Notify\Message\BaseMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Tests\TestAsset\Contact\TestContact;
use Notify\Message\Content\ContentProviderInterface;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class BaseMessageTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingMessageWithStringContent()
    {
        $message = $this->getMockForAbstractClass(BaseMessage::class, [
            new Recipients([
                new Recipient(new TestContact('test'))
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

        $message = $this->getMockForAbstractClass(BaseMessage::class, [
            new Recipients([
                new Recipient(new TestContact('test'))
            ]),
            $contentProvider
        ]);

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
    }

    public function testExceptionIsRaisedInCaseOfInvalidContentArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getMockForAbstractClass(BaseMessage::class, [
            new Recipients([
                new Recipient(new TestContact('test'))
            ]),
            false
        ]);
    }
}
