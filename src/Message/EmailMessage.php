<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Message\Actor\ActorInterface;

class EmailMessage extends AbstractMessage
{
    use HasOptionsTrait;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var ActorInterface
     */
    protected $from;

    public function __construct(
        array $recipients,
        string $subject,
        string $content,
        ActorInterface $from = null,
        Options $options = null
    ) {
        parent::__construct($recipients, $content);

        $this->subject = $subject;
        $this->from = $from;
        $this->options = $options;
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
