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

use PHPUnit\Framework\TestCase;
use Notify\Message\EmailMessage;
use Notify\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\ActorInterface;
use Notify\Message\Options;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailMessageTest extends TestCase
{
    public function testCreatingEmailWithRequiredArguments()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor('john@example.com', 'Test')
            ]),
            'Test',
            'test test test'
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertNull($message->getFrom());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    public function testCreatingEmailWithAllArguments()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor('john@example.com', 'Test')
            ]),
            'Test',
            'test test test',
            new Actor('test@example.com', 'Test'),
            new Options(['html' => true])
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(ActorInterface::class, $message->getFrom());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }
}
