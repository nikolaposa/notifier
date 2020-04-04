<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

class EmailMessage
{
    private $headers = [];
    private $subject = '';
    private $body = '';
    private $contentType = 'text/plain';

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function from(string $email, string $name = null)
    {
        $this->headers['From'][] = $this->createAddress($email, $name);

        return $this;
    }

    public function getFrom(): array
    {
        return $this->headers['From'] ?? [];
    }

    public function sender(string $email, string $name = null)
    {
        $this->headers['Sender'] = $this->createAddress($email, $name);

        return $this;
    }

    public function getSender(): string
    {
        return $this->headers['Sender'] ?? '';
    }

    public function replyTo(string $email, string $name = null)
    {
        $this->headers['Reply-To'][] = $this->createAddress($email, $name);

        return $this;
    }

    public function getReplyTo(): array
    {
        return $this->headers['Reply-To'] ?? [];
    }

    public function to(string $email, string $name = null)
    {
        $this->headers['To'][] = $this->createAddress($email, $name);

        return $this;
    }

    public function getTo(): array
    {
        return $this->headers['To'] ?? [];
    }

    public function cc(string $email, string $name = null)
    {
        $this->headers['Cc'][] = $this->createAddress($email, $name);

        return $this;
    }

    public function getCc(): array
    {
        return $this->headers['Cc'] ?? [];
    }

    public function bcc(string $email, string $name = null)
    {
        $this->headers['Bcc'][] = $this->createAddress($email, $name);

        return $this;
    }

    public function getBcc(): array
    {
        return $this->headers['Bcc'] ?? [];
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function textBody(string $textBody)
    {
        $this->body = $textBody;

        return $this;
    }

    public function htmlBody(string $htmlBody)
    {
        $this->body = $htmlBody;
        $this->headers['MIME-Version'] = '1.0';
        $this->headers['Content-type'] = 'text/html; charset=utf-8';

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    private function createAddress(string $email, ?string $name): string
    {
        return (null !== $name) ? $name . ' <' . $email . '>' : $email;
    }
}
