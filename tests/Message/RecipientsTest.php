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
use Notify\Message\Actor\EmptyRecipient;
use Notify\Tests\TestAsset\Contact\TestContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class RecipientsTest extends PHPUnit_Framework_TestCase
{
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

    public function testEmptyRecipientsAreNotAdded()
    {
        $recipients = new Recipients([
            new Recipient('Test1', new TestContact('test1')),
            null,
            new Recipient('Test2', new TestContact('test2')),
            new Recipient('Test3', new TestContact('test3')),
            new EmptyRecipient()
        ]);

        $this->assertCount(3, $recipients);
    }
}
