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
use Notify\Tests\TestAsset\Entity\User;
use Notify\Contact\Contacts;
use Notify\Contact\EmailContact;
use Notify\Message\EmailMessage;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotificationTest extends PHPUnit_Framework_TestCase
{
    protected $notification;

    protected function setUp()
    {
        parent::setUp();

        $this->notification = new TestNotification();
    }

    public function testGettingSupportedChannels()
    {
        $supportedChannels = $this->notification->getSupportedChannels();

        $this->assertEquals(['Email'], $supportedChannels);
    }

    public function testCheckingSupportedChannels()
    {
        $this->assertTrue($this->notification->isChannelSupported('Email'));
        $this->assertFalse($this->notification->isChannelSupported('Foobar'));
    }

    public function testCreatingMessage()
    {
        $emailMessage = $this->notification->getMessage(
            'Email',
            new User(new Contacts([
                new EmailContact('test@example.com')
            ]))
        );

        $this->assertInstanceOf(EmailMessage::class, $emailMessage);
    }
}
