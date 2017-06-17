<?php

declare(strict_types=1);

namespace Notify\Message;

use Notify\Message\Actor\ActorInterface;

class PushMessage
{
    use HasOptionsTrait;

    /**
     * @var ActorInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $message;

    public function __construct(
        ActorInterface $user,
        string $message,
        Options $options = null
    ) {
        $this->user = $user;
        $this->message = $message;
        $this->options = $options;
    }

    public function getUser() : ActorInterface
    {
        return $this->user;
    }
    
    public function getMessage() : string
    {
        return $this->message;
    }
}
