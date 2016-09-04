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
use Notify\Contact\EmailContact;
use Notify\Message\Actor\Actor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ActorTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingActor()
    {
        $actor = new Actor(new EmailContact('test@example.com'), 'Test');

        $this->assertEquals('test@example.com', $actor->getContact()->getValue());
        $this->assertEquals('Test', $actor->getName());
    }

    public function testNoActorNameByDefault()
    {
        $actor = new Actor(new EmailContact('test@example.com'));

        $this->assertEquals('test@example.com', $actor->getContact()->getValue());
        $this->assertNull($actor->getName());
    }

    public function testActorToStringConversion()
    {
        $recipient = new Actor(new EmailContact('test@example.com'), 'Test');

        $this->assertEquals('Test <test@example.com>', (string) $recipient);
    }

    public function testActorWithNoNameStringConversion()
    {
        $recipient = new Actor(new EmailContact('test@example.com'));

        $this->assertEquals('test@example.com', (string) $recipient);
    }
}
