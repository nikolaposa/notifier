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
    public function phoneNumbers()
    {
        return [
            ['1 (234) 567-8901 x1234', '123456789011234'],
            ['+381 64 111111', '+38164111111'],
            ['+12222222222 ', '+12222222222'],
        ];
    }

    /**
     * @dataProvider phoneNumbers
     */
    public function testPhoneNumberFiltering($raw, $filtered)
    {
        $phoneContact = new PhoneContact($raw);

        $this->assertEquals($filtered, $phoneContact->getValue());
    }
}
