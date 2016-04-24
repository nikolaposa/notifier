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
use Notify\Message\SMSMessage;
use Notify\Message\MessageInterface;
use Notify\Message\HasSenderInterface;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\PhoneContact;
use Notify\Message\Actor\ActorInterface;

/**
 *
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SMSMessageTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingSmsWithRequiredArguments()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test'
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(HasSenderInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertNull($message->getSender());
    }

    public function testCreatingSmsWithAllArguments()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
            ]),
            'test test test',
            new Actor(new PhoneContact('+11111111111'))
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(HasSenderInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(ActorInterface::class, $message->getSender());
    }

    public function testLimitingSmsContentLength()
    {
        $content = str_pad('test', 200, ' test');

        $message = new SMSMessage(
            new Recipients([
                new Actor(new PhoneContact('+12222222222'))
            ]),
            $content,
            new Actor(new PhoneContact('+11111111111'))
        );

        $this->assertEquals($content, $message->getRawContent());
        $this->assertNotEquals($message->getRawContent(), $message->getContent());
        $this->assertTrue(SMSMessage::CONTENT_LENGTH_LIMIT == strlen($message->getContent()));
    }
}
