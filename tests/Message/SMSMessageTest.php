<?php

declare(strict_types=1);

namespace Notify\Tests\Message;

use PHPUnit\Framework\TestCase;
use Notify\Message\SMSMessage;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\ActorInterface;

class SMSMessageTest extends TestCase
{
    public function testCreatingSmsWithRequiredArguments()
    {
        $message = new SMSMessage(
            new Actor('+12222222222'),
            'test test test'
        );

        $this->assertEquals('+12222222222', $message->getTo()->getContact());
        $this->assertEquals('test test test', $message->getText());
        $this->assertNull($message->getFrom());
    }

    public function testCreatingSmsWithAllArguments()
    {
        $message = new SMSMessage(
            new Actor('+12222222222'),
            'test test test',
            new Actor('+11111111111')
        );

        $this->assertEquals('+12222222222', $message->getTo()->getContact());
        $this->assertEquals('test test test', $message->getText());
        $this->assertInstanceOf(ActorInterface::class, $message->getFrom());
    }

    public function testLimitingSmsContentLength()
    {
        $content = str_pad('test', 200, ' test');

        $message = new SMSMessage(
            new Actor('+12222222222'),
            $content,
            new Actor('+11111111111')
        );

        $this->assertEquals($content, $message->getRawText());
        $this->assertNotEquals($message->getRawText(), $message->getText());
        $this->assertSame(SMSMessage::CONTENT_LENGTH_LIMIT, strlen($message->getText()));
    }
}
