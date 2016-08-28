<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Notification;

use PHPUnit_Framework_TestCase;
use Notify\Tests\TestAsset\Notification\TestNotification;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotificationTest extends PHPUnit_Framework_TestCase
{
    private $notification;

    protected function setUp()
    {
        parent::setUp();

        $this->notification = new TestNotification();
    }

    public function testGettingName()
    {
        $this->assertEquals('Test', $this->notification->getName());
    }
}
