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
use Notify\Contact\GenericContact;
use Notify\Message\Actor\GenericActor;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class GenericActorTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingActor()
    {
        $actor = new GenericActor(new GenericContact('test'), 'Test');

        $this->assertEquals('test', $actor->getContact()->getValue());
        $this->assertEquals('Test', $actor->getName());
    }

    public function testNoActorNameByDefault()
    {
        $actor = new GenericActor(new GenericContact('test'));

        $this->assertEquals('test', $actor->getContact()->getValue());
        $this->assertNull($actor->getName());
    }
}
