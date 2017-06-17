<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Recipients;
use Notify\Message\Actor\ActorInterface;

class SMSMessage extends AbstractMessage
{
    const CONTENT_LENGTH_LIMIT = 160;

    /**
     * @var ActorInterface
     */
    protected $from;

    public function __construct(
        Recipients $recipients,
        string $content,
        ActorInterface $from = null
    ) {
        parent::__construct($recipients, $content);

        $this->from = $from;
    }

    public function getContent() : string
    {
        $content = $this->content;

        return strlen($content) > self::CONTENT_LENGTH_LIMIT
            ? substr($content, 0, self::CONTENT_LENGTH_LIMIT)
            : $content;
    }

    public function getRawContent() : string
    {
        return $this->content;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function hasFrom() : bool
    {
        return null !== $this->from;
    }
}
