<?php

namespace Notify\Message\Actor;

use Countable;
use IteratorAggregate;
use ArrayIterator;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Recipients implements Countable, IteratorAggregate
{
    /**
     * @var RecipientInterface[]
     */
    private $recipients = [];

    public function __construct(array $recipients)
    {
        foreach ($recipients as $recipient) {
            if (! $recipient instanceof RecipientInterface || $recipient instanceof EmptyRecipient) {
                continue;
            }

            $this->recipients[] = $recipient;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->recipients);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->recipients);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->recipients);
    }
}
