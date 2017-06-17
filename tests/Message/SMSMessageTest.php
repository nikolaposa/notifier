<?php

declare(strict_types=1);

namespace Notify\Tests\Message;

use PHPUnit\Framework\TestCase;
use Notify\Message\SMSMessage;
use Notify\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\ActorInterface;

class SMSMessageTest extends TestCase
{
    public function testCreatingSmsWithRequiredArguments()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test'
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertNull($message->getFrom());
    }

    public function testCreatingSmsWithAllArguments()
    {
        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            'test test test',
            new Actor('+11111111111')
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(ActorInterface::class, $message->getFrom());
    }

    public function testLimitingSmsContentLength()
    {
        $content = str_pad('test', 200, ' test');

        $message = new SMSMessage(
            new Recipients([
                new Actor('+12222222222')
            ]),
            $content,
            new Actor('+11111111111')
        );

        $this->assertEquals($content, $message->getRawContent());
        $this->assertNotEquals($message->getRawContent(), $message->getContent());
        $this->assertTrue(SMSMessage::CONTENT_LENGTH_LIMIT == strlen($message->getContent()));
    }
}
