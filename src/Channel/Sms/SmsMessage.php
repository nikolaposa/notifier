<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

class SmsMessage
{
    /** @var string */
    protected $from;

    /** @var string */
    protected $to = '';

    /** @var string */
    protected $text = '';

    public function from(string $phoneNumber)
    {
        $this->from = $phoneNumber;

        return $this;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function to(string $phoneNumber)
    {
        $this->to = $phoneNumber;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function text(string $text)
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
