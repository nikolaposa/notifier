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
use Notify\Message\Actor\Sender;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SenderTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingSender()
    {
        $sender = new Sender('Test', new TestContact('test'));

        $this->assertEquals('Test', $sender->getName());
        $this->assertEquals('test', $sender->getContact()->getValue());
    }
}
