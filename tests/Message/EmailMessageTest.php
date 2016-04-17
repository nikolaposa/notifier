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
use Notify\Message\Actor\Recipient;
use Notify\Contact\GenericContact;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Actor\SenderInterface;
use Notify\Message\Options\EmailOptions;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessageTest extends PHPUnit_Framework_TestCase
{
    public function testEmailMessageImplementsAppropriateInterfaces()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient(new GenericContact('test'), 'Test')
            ]),
            'Test',
            'test test test',
            new EmptySender(),
            new EmailOptions()
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(HasSubjectInterface::class, $message);
        $this->assertInstanceOf(HasSenderInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(SenderInterface::class, $message->getSender());
        $this->assertInstanceOf(EmailOptions::class, $message->getOptions());
    }
}
