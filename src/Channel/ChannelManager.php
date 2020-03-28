<?php

declare(strict_types=1);

namespace Notifier\Channel;

class ChannelManager
{
    /** @var array<string,NotificationSender> */
    protected $channelNotificationSenderMap;

    public function __construct(array $channelNotificationSenderMap)
    {
        $this->channelNotificationSenderMap = $channelNotificationSenderMap;
    }

    public function getSender(string $channel): NotificationSender
    {
        return $this->channelNotificationSenderMap[$channel];
    }
}
