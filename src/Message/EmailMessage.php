<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Message\Actor\ActorInterface;

class EmailMessage
{
    use HasOptionsTrait;

    /**
     * @var ActorInterface[]
     */
    protected $to;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var ActorInterface
     */
    protected $from;

    public function __construct(
        array $to,
        string $subject,
        string $body,
        ActorInterface $from = null,
        Options $options = null
    ) {
        $this->to = $to;
        $this->body = $body;
        $this->subject = $subject;
        $this->from = $from;
        $this->options = $options;
    }

    public function getTo() : array
    {
        return $this->to;
    }

    public function getBody() : string
    {
        return $this->body;
    }

    public function getSubject() : string
    {
        return $this->subject;
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
