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
            $this->buildHeaders()
        );

        if (!$status) {
            $error = error_get_last();
            throw SendingMessageFailed::dueTo(new ErrorException($error['message'] ?? 'Email has not been accepted for delivery'));
        }
    }

    private function buildTo(): string
    {
        return implode(', ', $this->message->getTo());
    }

    private function buildSubject(): string
    {
        return $this->message->getSubject();
    }

    private function buildMessage(): string
    {
        return wordwrap($this->message->getBody(), self::MESSAGE_LINE_CHARACTERS_LIMIT);
    }

    private function buildHeaders(): string
    {
        $headers = array_map(function (string $name, $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            return "$name: $value";
        }, array_keys($this->message->getHeaders()), $this->message->getHeaders());

        return implode("\r\n", $headers);
    }
}
