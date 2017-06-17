<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Message;

use Notify\Message\Actor\ActorInterface;

final class DummyMessage
{
    /**
     * @var ActorInterface[]
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $content;

    public function __construct(array $recipients, string $content)
    {
        $this->recipients = $recipients;
        $this->content = $content;
    }

    public function getRecipients() : array
    {
        return $this->recipients;
    }

    public function getContent() : string
    {
        return $this->content;
    }
}
