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
use Notify\Message\Actor\EmptySender;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmptySenderTest extends PHPUnit_Framework_TestCase
{
    public function testSenderIsEmpty()
    {
        $sender = new EmptySender();

        $this->assertEmpty($sender->getName());
        $this->assertEmpty($sender->getContact());
    }
}
