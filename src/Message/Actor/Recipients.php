<?php

namespace Notify\Message\Actor;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use JsonSerializable;
use Notify\Exception\InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Recipients implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var ActorInterface[]
     */
    private $recipients = [];

    public function __construct(array $recipients)
    {
        foreach ($recipients as $recipient) {
            self::validateRecipient($recipient);

            $this->recipients[] = $recipient;
        }
    }

    private static function validateRecipient($recipient)
    {
        if (!$recipient instanceof ActorInterface) {
            throw new InvalidArgumentException(sprintf(
                '%s expects array of %s instances, %s given',
                __METHOD__,
                ActorInterface::class,
                is_object($recipient) ? get_class($recipient) : gettype($recipient)
            ));
        }
    }

    public function count()
    {
        return count($this->recipients);
    }

    public function isEmpty()
    {
        return empty($this->recipients);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->recipients);
    }

    public function toArray()
    {
        return $this->recipients;
    }

    public function jsonSerialize()
    {
        return array_map('strval', $this->toArray());
    }
}
