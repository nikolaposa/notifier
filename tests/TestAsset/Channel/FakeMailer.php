<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Channel;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Email\Mailer;
use Notifier\Channel\Exception\SendingMessageFailed;

final class FakeMailer implements Mailer
{
    /** @var EmailMessage[] */
    private $messages = [];

    public function send(EmailMessage $message): void
    {
        foreach ($message->to as $to) {
            if (!filter_var($to[0], FILTER_VALIDATE_EMAIL)) {
                throw new SendingMessageFailed('Invalid destination email address: ' . $to[0]);
            }
        }

        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
