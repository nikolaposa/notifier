<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Channel;

use Notifier\Channel\Exception\SendingMessageFailed;
use Notifier\Channel\Sms\SmsMessage;
use Notifier\Channel\Sms\Texter;

final class FakeTexter implements Texter
{
    /** @var SmsMessage[] */
    private $messages = [];

    public function send(SmsMessage $message): void
    {
        if ('' === $message->to) {
            throw new SendingMessageFailed('Invalid destination phone number');
        }

        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
