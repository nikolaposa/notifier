<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Channel;

use Notifier\Channel\Email\EmailMessage;
use Notifier\Channel\Email\Mailer;
use Notifier\Exception\SendingMessageFailed;

final class FakeMailer implements Mailer
{
    /** @var EmailMessage[] */
    private $messages = [];

    public function send(EmailMessage $message): void
    {
        foreach ($message->getTo() as $to) {
            $email = $to;
            if (preg_match('/^(?<name>[^<]*)<(?<email>.*)>[^>]*$/', $to, $matches)) {
                $email = $matches['email'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new SendingMessageFailed('Invalid destination email address: ' . $email);
            }
        }

        $this->messages[] = $message;
    }

    /**
     * @return EmailMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
