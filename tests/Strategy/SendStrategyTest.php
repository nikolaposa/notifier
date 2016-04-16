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
use Notify\Tests\TestAsset\Message\DummyMessage;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Recipients;
use Notify\Message\Actor\Recipient;
use Notify\Contact\GenericContact;

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
    }

    public function messages()
    {
        return [
            [
                [
                    new DummyMessage(
                        new Recipients([
                            new Recipient(new GenericContact('test'))
                        ]),
                        'test1'
                    ),
                    new DummyMessage(
                        new Recipients([
                            new Recipient(new GenericContact('test'))
                        ]),
                        'test2'
                    ),
                ]
            ],
            [
                [
                    new DummyMessage(
                        new Recipients([
                            new Recipient(new GenericContact('test'))
                        ]),
                        'test3'
                    ),
                ]
            ],
        ];
    }

    /**
     * @dataProvider messages
     */
    public function testSendingMessages(array $messages)
    {
        $strategy = new SendStrategy([
            DummyMessage::class => $this->testHandler,
        ]);

        $strategy->handle($messages);

        $sentMessages = $this->testHandler->getMessages();
        $this->assertNotEmpty($sentMessages);
        $this->assertCount(count($messages), $sentMessages);
    }

    public function testSendingSkippedInCaseOfUnsupportedMessageType()
    {
        $message = new DummyMessage(
            new Recipients([
                new Recipient(new GenericContact('test'))
            ]),
            'test test test'
        );

        $strategy = new SendStrategy([
            EmailMessage::class => $this->testHandler,
        ]);
        $strategy->handle([$message]);

        $this->assertEmpty($this->testHandler->getMessages());
    }
}
