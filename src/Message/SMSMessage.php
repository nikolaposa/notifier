<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Message\Actor\ActorInterface;

class SMSMessage
{
    const CONTENT_LENGTH_LIMIT = 160;

    /**
     * @var ActorInterface
     */
    protected $to;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var ActorInterface
     */
    protected $from;

    public function __construct(
        ActorInterface $to,
        string $text,
        ActorInterface $from = null
    ) {
        $this->to = $to;
        $this->text = $text;
        $this->from = $from;
    }
    
    public function getTo() : ActorInterface
    {
        return $this->to;
    }

    public function getText() : string
    {
        $text = $this->text;

        return strlen($text) > self::CONTENT_LENGTH_LIMIT
            ? substr($text, 0, self::CONTENT_LENGTH_LIMIT)
            : $text;
    }

    public function getRawText() : string
    {
        return $this->text;
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
