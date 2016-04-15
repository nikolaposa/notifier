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
use Notify\Contact\BaseContact;
use Notify\Tests\TestAsset\Contact\TestContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ContactTest extends PHPUnit_Framework_TestCase
{
    public function testGettingContactValue()
    {
        $contact = $this->getMockForAbstractClass(BaseContact::class, ['test']);

        $this->assertEquals('test', $contact->getValue());
    }

    public function testNoContactNameByDefault()
    {
        $contact = $this->getMockForAbstractClass(BaseContact::class, ['test']);

        $this->assertNull($contact->getName());
    }

    public function testSupplyingContactName()
    {
        $contact = $this->getMockForAbstractClass(BaseContact::class, ['test', 'John Doe']);

        $this->assertEquals('John Doe', $contact->getName());
    }

    public function testFilteringContact()
    {
        $contact = new TestContact('    test    ');

        $this->assertEquals('test', $contact->getValue());
    }
}
