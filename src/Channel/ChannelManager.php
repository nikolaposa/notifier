<?php

declare(strict_types=1);

namespace Notifier\Channel;

class ChannelManager
{
    /** @var Channel[] */
    protected $channels;

    public function __construct(Channel ...$channels)
    {
        foreach ($channels as $channel) {
            $this->channels[$channel->getName()] = $channel;
        }
    }

    public function get(string $channelName): Channel
    {
        return $this->channels[$channelName];
    }
}
