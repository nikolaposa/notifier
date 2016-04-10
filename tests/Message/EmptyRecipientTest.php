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
use Notify\Message\Actor\EmptyRecipient;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmptyRecipientTest extends PHPUnit_Framework_TestCase
{
    public function testRecipientIsEmpty()
    {
        $recipient = new EmptyRecipient();

        $this->assertEmpty($recipient->getName());
        $this->assertEmpty($recipient->getContact());
    }
}
