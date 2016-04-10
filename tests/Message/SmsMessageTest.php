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
use Notify\Message\SmsMessage;
use Notify\Message\MessageInterface;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Tests\TestAsset\Contact\TestContact;
use Notify\Message\Content\TextContent;
use Notify\Message\Content\ContentInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SmsMessageTest extends PHPUnit_Framework_TestCase
{
    public function testSmsMessageImplementsAppropriateInterfaces()
    {
        $message = new SmsMessage(
            new Recipients([
                new Recipient('Test', new TestContact('test'))
            ]),
            new TextContent('test test test')
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertInstanceOf(ContentInterface::class, $message->getContent());
    }
}
