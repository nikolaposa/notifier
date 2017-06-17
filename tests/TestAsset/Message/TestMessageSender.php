<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Message;

use Notify\Message\Sender\MessageSenderInterface;

final class TestMessageSender implements MessageSenderInterface
{
    /**
     * @var object[]
     */
    private $messages = [];

    public function send($message)
    {
        $this->messages[] = $message;
    }

    public function getMessages() : array
    {
        return $this->messages;
    }
}
