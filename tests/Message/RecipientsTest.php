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
use Notify\Message\Actor\Recipient;
use Notify\Message\Actor\RecipientInterface;
use Notify\Tests\TestAsset\Contact\TestContact;
use Notify\Tests\TestAsset\Entity\User;
use Notify\Message\EmailMessage;
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

    public function testCountingRecipients()
    {
        $recipients = new Recipients([
            new Recipient('Test1', new TestContact('test1')),
            new Recipient('Test2', new TestContact('test2')),
            new Recipient('Test3', new TestContact('test3')),
        ]);

        $this->assertCount(3, $recipients);
    }

    public function testTraversingRecipients()
    {
        $recipients = new Recipients([
            new Recipient('Test1', new TestContact('test1')),
            new Recipient('Test2', new TestContact('test2')),
            new Recipient('Test3', new TestContact('test3')),
        ]);

        foreach ($recipients as $recipient) {
            $this->assertInstanceOf(RecipientInterface::class, $recipient);
        }
    }

    public function testRecipientsToArrayConversion()
    {
        $recipients = new Recipients([
            new Recipient('Test1', new TestContact('test1')),
            new Recipient('Test2', new TestContact('test2')),
            new Recipient('Test3', new TestContact('test3')),
        ]);

        $this->assertInternalType('array', $recipients->toArray());
    }

    public function testCreatingRecipientsFromRecipientProviders()
    {
        $recipients = Recipients::fromRecipientProviders([
            new User('jd', 'John', 'Doe', 'jd@example.com'),
        ], EmailMessage::class);

        $this->count(1, $recipients);
    }

    public function testRecipientsCreationFailsInCaseOfInvalidRecipient()
    {
        $this->expectException(InvalidArgumentException::class);

        new Recipients(['invalid']);
    }
}
