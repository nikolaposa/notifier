<?php

declare(strict_types=1);

namespace Notifier\Channel\Email;

class EmailMessage
{
    /** @var array */
    public $from = [];

    /** @var array */
    public $to = [];

    /** @var string */
    public $subject = '';

    /** @var string */
    public $body = '';

    /** @var string */
    public $contentType = 'text/plain';

    public function from(string $email, string $name = '')
    {
        $this->from = [$email => $name];
        return $this;
    }

    public function to(string $email, string $name = '')
    {
        $this->to[]= [$email => $name];
        return $this;
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function body(string $body)
    {
        $this->body = $body;
        return $this;
    }

    public function contentType(string $contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }
}
