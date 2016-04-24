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
use Notify\Message\EmailMessage;
use Notify\Message\MessageInterface;
use Notify\Message\HasSubjectInterface;
use Notify\Message\HasSenderInterface;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\EmailContact;
use Notify\Message\Actor\ActorInterface;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessageTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingEmailWithRequiredArguments()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('john@example.com'), 'Test')
            ]),
            'Test',
            'test test test'
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(HasSubjectInterface::class, $message);
        $this->assertInstanceOf(HasSenderInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertNull($message->getSender());
        $this->assertInstanceOf(EmailOptions::class, $message->getOptions());
    }

    public function testCreatingEmailWithAllArguments()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('john@example.com'), 'Test')
            ]),
            'Test',
            'test test test',
            new Actor(new EmailContact('test@example.com'), 'Test'),
            new EmailOptions('text/html')
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(HasSubjectInterface::class, $message);
        $this->assertInstanceOf(HasSenderInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(ActorInterface::class, $message->getSender());
        $this->assertInstanceOf(EmailOptions::class, $message->getOptions());
    }
}
