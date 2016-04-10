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
use Notify\Message\Content\TextContent;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;

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

    public function mailer($to, $subject, $message, $additional_headers = null, $additional_parameters = null)
    {
        $this->sentParameters[] = func_get_args();
    }

    public function testSendWithDefaultOptions()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient('Test1', new EmailContact('test1@example.com')),
                new Recipient('Test2', new EmailContact('test2@example.com')),
            ]),
            'Test',
            new TextContent('test test test'),
            new EmptySender(),
            new EmailOptions()
        );

        $handler = new NativeMailerHandler(70, [$this, 'mailer']);
        $handler->send($message);

        $this->assertNotEmpty($this->sentParameters);
        $params = $this->sentParameters[0];
        $this->assertCount(5, $params);
        $this->assertEquals('Test1 <test1@example.com>,Test2 <test2@example.com>', $params[0]);
        $this->assertEquals('Test', $params[1]);
        $this->assertEquals('test test test', $params[2]);
        $this->assertEquals("Content-type: text/plain; charset=utf-8\r\n", $params[3]);
        $this->assertSame('', $params[4]);
    }
}
