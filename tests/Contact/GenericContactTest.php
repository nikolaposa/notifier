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
use Notify\Contact\GenericContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class GenericContactTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingContact()
    {
        $contact = new GenericContact('contact_val', 'contact_name');

        $this->assertEquals('contact_val', $contact->getValue());
        $this->assertEquals('contact_name', $contact->getName());
    }

    public function testNoContactNameByDefault()
    {
        $contact = new GenericContact('contact_val');

        $this->assertEquals('contact_val', $contact->getValue());
        $this->assertNull($contact->getName());
    }
}
