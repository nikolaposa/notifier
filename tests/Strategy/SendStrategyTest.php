<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\Strategy;

use PHPUnit_Framework_TestCase;
use Notify\Strategy\SendStrategy;
use Notify\Message\Handler\TestHandler;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Message\Content\TextContent;
use Notify\Contact\EmailContact;
use Notify\Message\Actor\EmptySender;
use Notify\Message\Options\EmailOptions;
use Notify\Tests\TestAsset\Message\DummyMessage;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class HandlersStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SendStrategy
     */
    private $strategy;

    /**
     * @var TestHandler
     */
    private $testHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->testHandler = new TestHandler();

        $this->strategy = new SendStrategy([
            EmailMessage::class => $this->testHandler,
        ]);
    }

    public function testSendingMessages()
    {
        $message = new EmailMessage(
            new Recipients([
                new Recipient('John Doe', new EmailContact('test1@example.com'))
            ]),
            'Test',
            new TextContent('test test test'),
            new EmptySender(),
            new EmailOptions()
        );

        $this->strategy->handle([
            $message
        ]);

        $sentMessages = $this->testHandler->getMessages();
        $this->assertNotEmpty($sentMessages);
        $sentMessage = current($sentMessages);
        $this->assertInstanceOf(EmailMessage::class, $sentMessage);
        $this->assertEquals('Test', $sentMessage->getSubject());
        $this->assertEquals('test test test', $sentMessage->getContent()->get());
        $this->assertCount(1, $sentMessage->getRecipients());
    }

    public function testSendingSkippedInCaseOfUnsupportedMessageType()
    {
        $message = new DummyMessage(
            new Recipients([
                new Recipient('John Doe', new EmailContact('test1@example.com'))
            ]),
            new TextContent('test test test')
        );

        $this->strategy->handle([$message]);

        $this->assertEmpty($this->testHandler->getMessages());
    }
}
