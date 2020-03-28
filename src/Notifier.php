<?php

declare(strict_types=1);

namespace Notifier;

use Notifier\Channel\ChannelManager;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;
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
            foreach ($channels as $channel) {
                $this->doSend($notification, $recipient, $channel);
            }
        }
    }

    private function doSend(Notification $notification, Recipient $recipient, string $channel): void
    {
        $this->channelManager->getSender($channel)->send($notification, $recipient);
    }
}
