<?php

declare(strict_types=1);

namespace Notify;

final class NotificationDerivative
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var NotificationInterface
     */
    private $notification;
    
    public function __construct(string $channel, NotificationInterface $notification)
    {
        $this->channel = $channel;
        $this->notification = $notification;
    }
    
    public function getChannel() : string
    {
        return $this->channel;
    }
    
    public function getNotification() : NotificationInterface
    {
        return $this->notification;
    }
}
