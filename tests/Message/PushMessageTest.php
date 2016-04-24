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
use Notify\Message\PushMessage;
use Notify\Message\MessageInterface;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\MobileDeviceContact;
use Notify\Message\Options\Options;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PushMessageTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingPushWithRequiredArguments()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('Token1'))
            ]),
            'test test test'
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    public function testCreatingPushWithAllArguments()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor(new MobileDeviceContact('Token1'))
            ]),
            'test test test',
            new Options(['sound' => 'example'])
        );

        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }
}
