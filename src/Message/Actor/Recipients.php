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

    public static function fromRecipientProviders(
        array $recipientProviders,
        $messageType,
        $notificationType = null
    ) {
        $recipients = [];

        foreach ($recipientProviders as $recipientProvider) {
            self::validateRecipientProvider($recipientProvider);

            /* @var $recipientProvider ProvidesRecipientInterface */

            $recipient = $recipientProvider->getMessageRecipient($messageType, $notificationType);

            if (null === $recipient) {
                continue;
            }

            $recipients[] = $recipient;
        }

        return new self($recipients);
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

    private static function validateRecipientProvider($recipientProvider)
    {
        if (!$recipientProvider instanceof ProvidesRecipientInterface) {
            throw new InvalidArgumentException(sprintf(
                '%s expects array of %s instances',
                __FUNCTION__,
                ProvidesRecipientInterface::class
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
