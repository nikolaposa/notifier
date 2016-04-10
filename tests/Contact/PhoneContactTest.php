<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Contact;

use PHPUnit_Framework_TestCase;
use Notify\Contact\PhoneContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PhoneContactTest extends PHPUnit_Framework_TestCase
{
    public function testPhoneContactCreation()
    {
        $contact = new PhoneContact('+123456', 'John Doe');
        $this->assertEquals('+123456', $contact->getValue());
        $this->assertEquals('John Doe', $contact->getName());
    }

    public function testPhoneNumberFiltering()
    {
        $contact = new PhoneContact('   +123456   ');
        $this->assertEquals('+123456', $contact->getValue());
    }
}
