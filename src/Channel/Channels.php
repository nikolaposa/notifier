<?php

declare(strict_types=1);

namespace Notifier\Channel;

use ArrayIterator;
use IteratorAggregate;
use Notifier\Channel\Exception\ChannelNotFound;
use Traversable;

class Channels implements IteratorAggregate
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
        if (!isset($this->channels[$channelName])) {
            throw ChannelNotFound::byName($channelName);
        }

        return $this->channels[$channelName];
    }

    /**
     * @return Traversable|Channel[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->channels);
    }
}
