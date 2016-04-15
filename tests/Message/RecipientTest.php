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
use Notify\Tests\TestAsset\Contact\TestContact;
use Notify\Message\Actor\Recipient;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class RecipientTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingRecipient()
    {
        $recipient = new Recipient(new TestContact('test'), 'Test');

        $this->assertEquals('test', $recipient->getContact()->getValue());
        $this->assertEquals('Test', $recipient->getName());
    }
}
