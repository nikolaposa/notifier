<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message\Sender;

use PHPUnit_Framework_TestCase;
use Notify\Message\Sender\NativeMailer;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Contact\EmailContact;
use Notify\Message\Options\Options;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\Sender\Exception\UnsupportedMessageException;
use Notify\Message\Sender\Exception\RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NativeMailerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $sentParameters;

    protected function setUp()
    {
        parent::setUp();

        $this->sentParameters = [];
    }

    public function mailer()
    {
        $this->sentParameters[] = func_get_args();

        return true;
    }

    public function mailerError()
    {
        return false;
    }

    private function getMailer($maxColumnWidth = 70)
    {
        return new NativeMailer($maxColumnWidth, [$this, 'mailer']);
    }

    public function testSendWithDefaultOptions()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com'), 'Test1'),
                new Actor(new EmailContact('test2@example.com'), 'Test2'),
            ]),
            'Test',
            'test test test'
        );

        $this->getMailer()->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals('Test1 <test1@example.com>,Test2 <test2@example.com>', $params[0]);
        $this->assertEquals('Test', $params[1]);
        $this->assertEquals('test test test', $params[2]);
        $this->assertEquals("Content-type: text/plain; charset=utf-8\r\n", $params[3]);
        $this->assertSame('', $params[4]);
    }

    public function testExceptionIsRaisedInCaseOfUnsupportedMessageType()
    {
        $this->expectException(UnsupportedMessageException::class);

        $message = new DummyMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com'))
            ]),
            'test test test'
        );

        $this->getMailer()->send($message);
    }

    public function testEmailContentWordWrap()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com')),
                new Actor(new EmailContact('test2@example.com')),
            ]),
            'Test',
            'test test test'
        );

        $this->getMailer(4)->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals("test\ntest\ntest", $params[2]);
    }

    public function testCustomEmailContentType()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com')),
                new Actor(new EmailContact('test2@example.com')),
            ]),
            'Test',
            'test test test',
            null,
            new Options([
                'content_type' => 'text/html',
            ])
        );

        $this->getMailer()->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals("Content-type: text/html; charset=utf-8\r\nMIME-Version: 1.0\r\n", $params[3]);
    }

    public function testEmailSenderHeaders()
    {
        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com')),
            ]),
            'Test',
            'test test test',
            new Actor(new EmailContact('john.doe@example.com'))
        );

        $this->getMailer()->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertContains("From: john.doe@example.com\r\nReply-To: john.doe@example.com", $params[3]);
    }

    public function testExceptionIsRaisedIfEmailNotDelivered()
    {
        $this->expectException(RuntimeException::class);

        $message = new EmailMessage(
            new Recipients([
                new Actor(new EmailContact('test1@example.com')),
                new Actor(new EmailContact('test2@example.com')),
            ]),
            'Test',
            'test test test'
        );

        $messageSender = new NativeMailer(70, [$this, 'mailerError']);
        $messageSender->send($message);
    }
}
