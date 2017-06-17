<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Recipients;

abstract class AbstractMessage
{
    /**
     * @var Recipients
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $content;

    public function __construct(Recipients $recipients, string $content)
    {
        $this->recipients = $recipients;
        $this->content = $content;
    }

    public function getRecipients() : Recipients
    {
        return $this->recipients;
    }

    public function getContent() : string
    {
        return $this->content;
    }
}
