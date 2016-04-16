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
     * @var RecipientInterface[]
     */
    private $recipients = [];

    public function __construct(array $recipients)
    {
        foreach ($recipients as $recipient) {
            if (!$recipient instanceof RecipientInterface) {
                throw new InvalidArgumentException(sprintf(
                    '%s expects array of %s instances, %s given',
                    __METHOD__,
                    RecipientInterface::class,
                    is_object($recipient) ? get_class($recipient) : gettype($recipient)
                ));
            }

            $this->recipients[] = $recipient;
        }
    }

    public static function fromRecipientProviders(
        array $recipientProviders,
        $messageType,
        $notificationId = null
    ) {
        $recipients = [];

        foreach ($recipientProviders as $recipientProvider) {
            if (!$recipientProvider instanceof ProvidesRecipientInterface) {
                throw new InvalidArgumentException(sprintf(
                    '%s expects array of %s instances',
                    __FUNCTION__,
                    ProvidesRecipientInterface::class
                ));
            }

            $recipient = $recipientProvider->getMessageRecipient($messageType, $notificationId);

            if (null === $recipient || $recipient instanceof EmptyRecipient) {
                continue;
            }

            $recipients[] = $recipient;
        }

        return new self($recipients);
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

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->recipients;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map('strval', $this->toArray());
    }
}
