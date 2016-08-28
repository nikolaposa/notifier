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
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\ActorInterface;
use Notify\Contact\GenericContact;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class RecipientsTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingEmptyRecipients()
    {
        $recipients = new Recipients([]);

        $this->assertTrue($recipients->isEmpty());
    }

    public function testRecipientsCreationFailsInCaseOfInvalidRecipient()
    {
        $this->expectException(InvalidArgumentException::class);

        new Recipients(['invalid']);
    }

    public function testCountingRecipients()
    {
        $recipients = new Recipients([
            new Actor(new GenericContact('test1')),
            new Actor(new GenericContact('test2')),
            new Actor(new GenericContact('test3')),
        ]);

        $this->assertCount(3, $recipients);
    }

    public function testTraversingRecipients()
    {
        $recipients = new Recipients([
            new Actor(new GenericContact('test1')),
            new Actor(new GenericContact('test2')),
            new Actor(new GenericContact('test3')),
        ]);

        foreach ($recipients as $recipient) {
            $this->assertInstanceOf(ActorInterface::class, $recipient);
        }
    }

    public function testRecipientsToArrayConversion()
    {
        $recipients = new Recipients([
            new Actor(new GenericContact('test1')),
            new Actor(new GenericContact('test2')),
            new Actor(new GenericContact('test3')),
        ]);

        $this->assertInternalType('array', $recipients->toArray());
    }

    public function testJsonSerializeRecipients()
    {
        $recipients = new Recipients([
            new Actor(new GenericContact('test1')),
            new Actor(new GenericContact('test2')),
            new Actor(new GenericContact('test3')),
        ]);

        $this->assertEquals(['test1', 'test2', 'test3'], $recipients->jsonSerialize());
    }
}
