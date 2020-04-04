<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

use Notifier\Channel\Channel;
use Notifier\Exception\SendingMessageFailed;
use Notifier\Exception\SendingNotificationFailed;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

final class EmailChannel implements Channel
{
    public const NAME = 'email';

    /** @var Mailer */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function send(Notification $notification, Recipient $recipient): void
    {
        if (!$notification instanceof EmailNotification) {
            return;
        }

        if (null === ($recipientEmail = $recipient->getRecipientContact(self::NAME, $notification))) {
            return;
        }

        $message = $notification->toEmailMessage($recipient);
        $message->to($recipientEmail, $recipient->getRecipientName());

        try {
            $this->mailer->send($message);
        } catch (SendingMessageFailed $error) {
            throw SendingNotificationFailed::for(self::NAME, $notification, $recipient, $error);
        }
    }
}
