<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

use Notifier\Channel\Exception\SendingNotificationFailed;
use Notifier\Channel\NotificationSender;
use Notifier\Notification\EmailNotification;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

final class SimpleEmailNotificationSender implements NotificationSender
{
    private const DEFAULT_MAX_COLUMN_WIDTH = 70;

    /** @var int */
    private $maxColumnWidth;

    /** @var EmailMessage */
    private $message;

    public function __construct($maxColumnWidth = self::DEFAULT_MAX_COLUMN_WIDTH)
    {
        $this->maxColumnWidth = (int) $maxColumnWidth;
    }

    public function send(Notification $notification, Recipient $recipient): void
    {
        if (!$notification instanceof EmailNotification) {
            return;
        }

        $this->message = $notification->toEmailMessage($recipient);
        $this->message->to(
            $recipient->getRecipientContact('email', $notification),
            $recipient->getRecipientName()
        );

        $this->doSend();
    }

    private function doSend(): void
    {
        $status = mail(
            $this->buildMailTo(),
            $this->buildMailSubject(),
            $this->buildMailMessage(),
            $this->buildMailHeaders()
        );

        if (!$status) {
            throw new SendingNotificationFailed('Email has not been accepted for delivery');
        }
    }

    private function buildMailTo(): string
    {
        $toList = [];

        foreach ($this->message->to as $email => $name) {
            $to = $email;

            if ('' !== $name) {
                $to = $name . ' <' . $to . '>';
            }

            $toList[] = $to;
        }

        return implode(',', $toList);
    }

    private function buildMailSubject(): string
    {
        return $this->message->subject;
    }

    private function buildMailMessage(): string
    {
        return wordwrap($this->message->body, $this->maxColumnWidth);
    }

    private function buildMailHeaders(): string
    {
        $headers = 'Content-type: ' . $this->message->contentType . '; charset=utf-8' . "\r\n";

        if ($this->message->contentType === 'text/html') {
            $headers .= "MIME-Version: 1.0\r\n";
        }

        if ([] !== $this->message->from) {
            $headers .= 'From: ' . key($this->message->from) . "\r\n";
        }

        return $headers;
    }
}
