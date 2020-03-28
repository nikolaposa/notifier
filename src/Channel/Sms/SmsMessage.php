<?php

declare(strict_types=1);

namespace Notifier\Channel\Sms;

class SmsMessage
{
    /** @var string */
    public $from = '';

    /** @var string */
    public $to;

    /** @var string */
    public $text;

    public function from(string $phoneNumber)
    {
        $this->from = $phoneNumber;
        return $this;
    }

    public function to(string $phoneNumber)
    {
        $this->to = $phoneNumber;
        return $this;
    }

    public function text(string $text)
    {
        $this->text = $text;
        return $this;
    }
}
