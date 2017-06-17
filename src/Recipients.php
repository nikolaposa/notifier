<?php

declare(strict_types=1);

namespace Notify;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Notify\Message\Actor\Actor;

class Recipients implements Countable, IteratorAggregate
{
    /**
     * @var RecipientInterface[]
     */
    private $recipients;

    public function __construct(RecipientInterface ...$recipients)
    {
        $this->recipients = $recipients;
    }

    public static function fromArray(array $recipients)
    {
        return new static(...$recipients);
    }

    public function count()
    {
        return count($this->recipients);
    }

    public function isEmpty()
    {
        return (0 === $this->count());
    }

    public function getIterator()
    {
        return new ArrayIterator($this->recipients);
    }

    public function filter(NotificationDerivative $notificationDerivative) : Recipients
    {
        return $this->fromArray(array_filter($this->recipients, function (RecipientInterface $recipient) use ($notificationDerivative) {
            return $recipient->shouldBeNotified($notificationDerivative);
        }));
    }

    public function toMessageRecipients(NotificationDerivative $notificationDerivative) : array
    {
        return array_map(function (RecipientInterface $recipient) use ($notificationDerivative) {
            return new Actor(
                $recipient->getRecipientContact($notificationDerivative),
                $recipient->getRecipientName()
            );
        }, $this->recipients);
    }
}
