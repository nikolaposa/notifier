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
use Notify\Message\Actor\ProvidesRecipientInterface;
use Notify\Contact\GenericContact;
use Notify\Message\EmailMessage;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class RecipientsTest extends PHPUnit_Framework_TestCase
{
    private function mockRecipientProvider($recipient = null)
    {
        $provider = $this->getMock(ProvidesRecipientInterface::class);
        $provider->expects($this->once())
            ->method('getMessageRecipient')
            ->will($this->returnValue($recipient));

        return $provider;
    }

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
            new Recipient(new GenericContact('test1')),
            new Recipient(new GenericContact('test2')),
            new Recipient(new GenericContact('test3')),
        ]);

        $this->assertCount(3, $recipients);
    }

    public function testTraversingRecipients()
    {
        $recipients = new Recipients([
            new Recipient(new GenericContact('test1')),
            new Recipient(new GenericContact('test2')),
            new Recipient(new GenericContact('test3')),
        ]);

        foreach ($recipients as $recipient) {
            $this->assertInstanceOf(RecipientInterface::class, $recipient);
        }
    }

    public function testRecipientsToArrayConversion()
    {
        $recipients = new Recipients([
            new Recipient(new GenericContact('test1')),
            new Recipient(new GenericContact('test2')),
            new Recipient(new GenericContact('test3')),
        ]);

        $this->assertInternalType('array', $recipients->toArray());
    }

    public function testJsonSerializeRecipients()
    {
        $recipients = new Recipients([
            new Recipient(new GenericContact('test1')),
            new Recipient(new GenericContact('test2')),
            new Recipient(new GenericContact('test3')),
        ]);

        $this->assertEquals(['test1', 'test2', 'test3'], $recipients->jsonSerialize());
    }

    public function testCreatingRecipientsFromRecipientProviders()
    {
        $recipients = Recipients::fromRecipientProviders([
            $this->mockRecipientProvider(new Recipient(new GenericContact('test1'))),
        ], EmailMessage::class);

        $this->assertCount(1, $recipients);
    }

    public function testCreatingRecipientsFromRecipientProvidersFailsInCaseOfInvalidProvider()
    {
        $this->expectException(InvalidArgumentException::class);

        Recipients::fromRecipientProviders([
            'invalid',
        ], EmailMessage::class);
    }

    public function testCreatingRecipientsFromRecipientProvidersSkippedIfRecipientNotResolved()
    {
        $recipients = Recipients::fromRecipientProviders([
            $this->mockRecipientProvider(null),
        ], EmailMessage::class);

        $this->assertTrue($recipients->isEmpty());
    }
}
