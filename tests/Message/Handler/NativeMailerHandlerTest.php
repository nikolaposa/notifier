<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Message\Handler;

use PHPUnit_Framework_TestCase;
use Notify\Message\Handler\NativeMailerHandler;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Contact\EmailContact;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Exception\UnsupportedMessageException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NativeMailerHandlerTest extends PHPUnit_Framework_TestCase
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
    }

    private function getHandler($maxColumnWidth = 70)
    {
        return new NativeMailerHandler($maxColumnWidth, [$this, 'mailer']);
    }

    public function testSendWithDefaultOptions()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient(new EmailContact('test1@example.com'), 'Test1'),
                new Recipient(new EmailContact('test2@example.com'), 'Test2'),
            ]),
            'Test',
            'test test test',
            new EmptySender(),
            new EmailOptions()
        );

        $this->getHandler()->send($message);

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
                new Recipient(new EmailContact('test1@example.com'))
            ]),
            'test test test'
        );

        $this->getHandler()->send($message);
    }

    public function testEmailContentWordWrap()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient(new EmailContact('test1@example.com')),
                new Recipient(new EmailContact('test2@example.com')),
            ]),
            'Test',
            'test test test',
            new EmptySender(),
            new EmailOptions()
        );

        $this->getHandler(4)->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals("test\ntest\ntest", $params[2]);
    }

    public function testCustomEmailContentType()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient(new EmailContact('test1@example.com')),
                new Recipient(new EmailContact('test2@example.com')),
            ]),
            'Test',
            'test test test',
            new EmptySender(),
            new EmailOptions('text/html')
        );

        $this->getHandler()->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals("Content-type: text/html; charset=utf-8\r\nMIME-Version: 1.0\r\n", $params[3]);
    }
}
