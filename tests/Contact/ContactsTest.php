<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Contact;

use PHPUnit_Framework_TestCase;
use Notify\Contact\Contacts;
use Notify\Contact\ContactInterface;
use Notify\Contact\EmailContact;
use Notify\Contact\PhoneContact;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ContactsTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingEmptyContacts()
    {
        $contacts = new Contacts();

        $this->assertTrue($contacts->isEmpty());
    }

    public function testContactsCreationFailsInCaseOfInvalidContact()
    {
        $this->expectException(InvalidArgumentException::class);

        new Contacts(['invalid']);
    }

    public function testCountingContacts()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home1'),
            new EmailContact('test2@example.com', 'home2'),
            new EmailContact('test3@example.com', 'work'),
        ]);

        $this->assertCount(3, $contacts);
    }

    public function testTraversingContacts()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home1'),
            new EmailContact('test2@example.com', 'home2'),
            new EmailContact('test3@example.com', 'work'),
        ]);

        foreach ($contacts as $contact) {
            $this->assertInstanceOf(ContactInterface::class, $contact);
        }
    }

    public function testGettingFirstContact()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home1'),
            new EmailContact('test2@example.com', 'home2'),
            new EmailContact('test3@example.com', 'work'),
        ]);

        $contact = $contacts->first();
        $this->assertInstanceOf(ContactInterface::class, $contact);
        $this->assertEquals('test1@example.com', $contact->getValue());
    }

    public function testGettingLastContact()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home1'),
            new EmailContact('test2@example.com', 'home2'),
            new EmailContact('test3@example.com', 'work'),
        ]);

        $contact = $contacts->last();
        $this->assertInstanceOf(ContactInterface::class, $contact);
        $this->assertEquals('test3@example.com', $contact->getValue());
    }

    public function testAddingContact()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home1'),
        ]);

        $this->assertCount(1, $contacts);

        $contacts->add(new EmailContact('test2@example.com', 'home2'));

        $this->assertCount(2, $contacts);
    }

    public function testHasContactTypeCheck()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com'),
        ]);

        $this->assertTrue($contacts->has(EmailContact::class));
        $this->assertFalse($contacts->has(PhoneContact::class));
    }

    public function testGettingContactsByType()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home'),
            new PhoneContact('123456', 'mobile'),
            new EmailContact('test2@example.com', 'work'),
        ]);

        $emailContacts = $contacts->getAll(EmailContact::class);

        $this->assertInstanceOf(Contacts::class, $emailContacts);
        $this->assertCount(2, $emailContacts);
    }

    public function testGettingOneContactByType()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'home'),
            new PhoneContact('123456', 'mobile'),
            new EmailContact('test2@example.com', 'work'),
        ]);

        $emailContact = $contacts->getOne(EmailContact::class);

        $this->assertInstanceOf(EmailContact::class, $emailContact);
    }

    public function testGettingContactOfTypeThatDoesNotExist()
    {
        $contacts = new Contacts([
            new PhoneContact('123456'),
        ]);

        $this->assertFalse($contacts->getOne(EmailContact::class));
    }

    public function testFindingContact()
    {
        $contacts = new Contacts([
            new EmailContact('test1@example.com', 'default'),
        ]);

        $contact = $contacts->find(EmailContact::class, 'default');

        $this->assertInstanceOf(EmailContact::class, $contact);
        $this->assertEquals('test1@example.com', $contact->getValue());
    }

    public function testFindContactReturnsFalseIfContactNotFound()
    {
        $contacts = new Contacts([
            new PhoneContact('123456'),
        ]);

        $this->assertFalse($contacts->find(EmailContact::class, 'default'));
    }
}
