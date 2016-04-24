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
use Notify\Contact\MobileDeviceContact;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class MobileDeviceContactTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingParametrizedMobileDeviceContact()
    {
        $parameters = ['badge' => 5];

        $mobileDevice = new MobileDeviceContact('Token1', null, $parameters);

        $this->assertEquals($parameters, $mobileDevice->getParameters());
    }
}
