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
use Notify\Contact\EmailContact;
use Notify\Exception\InvalidContactException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class EmailContactTest extends PHPUnit_Framework_TestCase
{
    public function testEmailValidation()
    {
        $this->expectException(InvalidContactException::class);
        $this->expectExceptionMessage('"invalid" is not a valid email address');

        new EmailContact('invalid');
    }
}
