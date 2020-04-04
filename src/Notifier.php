<?php

declare(strict_types=1);

namespace Notifier;

use Notifier\Channel\Channels;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipients;

class Notifier
{
    /** @var Channels */
    protected $channels;

    public function __construct(Channels $channels)
    {
        $this->channels = $channels;
    }

    public function send(Notification $notification, Recipients $recipients): void
    {
        foreach ($recipients as $recipient) {
            foreach ($this->channels as $channel) {
                $channel->send($notification, $recipient);
            }
        }
    }

    public function sendVia(string $channelName, Notification $notification, Recipients $recipients): void
    {
        $channel = $this->channels->get($channelName);

        foreach ($recipients as $recipient) {
            $channel->send($notification, $recipient);
        }
    }
}
