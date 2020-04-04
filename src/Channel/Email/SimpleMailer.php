<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

use ErrorException;
use Notifier\Channel\Exception\SendingMessageFailed;

final class SimpleMailer implements Mailer
{
    private const MESSAGE_LINE_CHARACTERS_LIMIT = 70;

    /** @var callable|null */
    private $handler;

    /** @var EmailMessage */
    private $message;

    public function __construct(callable $handler = null)
    {
        $this->handler = $handler;
    }

    public function send(EmailMessage $message): void
    {
        $this->message = $message;

        $status = call_user_func(
            $this->handler ?? 'mail',
            $this->buildTo(),
            $this->buildSubject(),
            $this->buildMessage(),
            $this->buildMailHeaders()
        );

        if (!$status) {
            $error = error_get_last();
            throw SendingMessageFailed::dueTo(new ErrorException($error['message'] ?? 'Email has not been accepted for delivery'));
        }
    }

    private function buildTo(): string
    {
        $toList = [];

        foreach ($this->message->to as $to) {
            [$email, $name] = $to;

            $toList[] = (null !== $name) ? $name . ' <' . $email . '>' : $email;
        }

        return implode(',', $toList);
    }

    private function buildSubject(): string
    {
        return $this->message->subject;
    }

    private function buildMessage(): string
    {
        return wordwrap($this->message->body, self::MESSAGE_LINE_CHARACTERS_LIMIT);
    }

    private function buildMailHeaders(): string
    {
        $headers = 'Content-type: ' . $this->message->contentType . '; charset=utf-8' . "\r\n";

        if ($this->message->contentType === 'text/html') {
            $headers .= "MIME-Version: 1.0\r\n";
        }

        if (null !== $this->message->from) {
            $headers .= 'From: ' . $this->message->from[0] . "\r\n";
        }

        return $headers;
    }
}
