<?php

declare(strict_types=1);

namespace Notify\Tests\Message;

use PHPUnit\Framework\TestCase;
use Notify\Message\PushMessage;
use Notify\Recipients;
use Notify\Message\Actor\Actor;
use Notify\Message\Options;

class PushMessageTest extends TestCase
{
    public function testCreatingPushWithRequiredArguments()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor('Token1')
            ]),
            'test test test'
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }

    public function testCreatingPushWithAllArguments()
    {
        $message = new PushMessage(
            new Recipients([
                new Actor('Token1')
            ]),
            'test test test',
            new Options(['sound' => 'example'])
        );

        $this->assertInstanceOf(Recipients::class, $message->getRecipients());
        $this->assertEquals('test test test', $message->getContent());
        $this->assertInstanceOf(Options::class, $message->getOptions());
    }
}
