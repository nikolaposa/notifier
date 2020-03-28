<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Message;

use Notifier\Channel\NotificationSender;

final class TestNotificationSender implements NotificationSender
{
    /** @var object[] */
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
