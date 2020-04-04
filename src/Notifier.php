<?php

declare(strict_types=1);

namespace Notifier;

use Notifier\Channel\ChannelManager;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipients;

class Notifier
{
    /** @var ChannelManager */
    protected $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function send(Notification $notification, Recipients $recipients): void
    {
        $channels = $notification->getSupportedChannels();

        foreach ($recipients as $recipient) {
            foreach ($channels as $channelName) {
                $this->channelManager->get($channelName)->send($notification, $recipient);
            }
        }
    }

    public function sendVia(string $channelName, Notification $notification, Recipients $recipients): void
    {
        $channel = $this->channelManager->get($channelName);

        foreach ($recipients as $recipient) {
            $channel->send($notification, $recipient);
        }
    }
}
