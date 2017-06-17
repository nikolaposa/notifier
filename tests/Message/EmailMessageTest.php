<?php

declare(strict_types=1);

namespace Notify\Tests\Message;

use PHPUnit\Framework\TestCase;
use Notify\Message\EmailMessage;
use Notify\Message\Actor\Actor;
use Notify\Message\Actor\ActorInterface;
use Notify\Message\Options;

class EmailMessageTest extends TestCase
{
    public function testCreatingEmailWithRequiredArguments()
    {
        $message = new EmailMessage(
            [
                new Actor('john@example.com', 'Test')
            ],
            'Test',
            'test test test'
        );

        $this->assertCount(1, $message->getTo());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getBody());
        $this->assertNull($message->getFrom());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    public function testCreatingEmailWithAllArguments()
    {
        $message = new EmailMessage(
            [
                new Actor('john@example.com', 'Test')
            ],
            'Test',
            'test test test',
            new Actor('test@example.com', 'Test'),
            new Options(['html' => true])
        );

        $this->assertCount(1, $message->getTo());
        $this->assertEquals('Test', $message->getSubject());
        $this->assertEquals('test test test', $message->getBody());
        $this->assertInstanceOf(ActorInterface::class, $message->getFrom());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }
}
