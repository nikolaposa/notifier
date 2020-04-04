<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

use Notifier\Channel\Channel;
use Notifier\Exception\SendingMessageFailed;
use Notifier\Exception\SendingNotificationFailed;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

final class SmsChannel implements Channel
{
    public const NAME = 'sms';

    /** @var Texter */
    private $texter;

    public function __construct(Texter $texter)
    {
        $this->texter = $texter;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function send(Notification $notification, Recipient $recipient): void
    {
        if (!$notification instanceof SmsNotification) {
            return;
        }

        if (null === ($recipientPhoneNumber = $recipient->getRecipientContact(self::NAME, $notification))) {
            return;
        }

        $message = $notification->toSmsMessage($recipient);
        $message->to($recipientPhoneNumber);

        try {
            $this->texter->send($message);
        } catch (SendingMessageFailed $error) {
            throw SendingNotificationFailed::for(self::NAME, $notification, $recipient, $error);
        }
    }
}
