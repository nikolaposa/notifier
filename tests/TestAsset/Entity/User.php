<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Entity;

use Notify\RecipientInterface;
use Notify\NotificationInterface;

class User implements RecipientInterface
{
    /**
     * @var array
     */
    private $contacts;

    /**
     * @var array
     */
    private $notified = [];

    public function __construct(array $contacts)
    {
        $this->contacts = $contacts;
    }

    public function getRecipientName() : string
    {
        return 'John Doe';
    }

    public function getRecipientContact(string $channel, NotificationInterface $notification) : string
    {
        if (!array_key_exists($channel, $this->contacts)) {
            throw new \RuntimeException(sprintf(
                'User does not accept notifications through %s channel',
                $channel
            ));
        }

        return $this->contacts[$channel];
    }

    public function acceptsNotification(NotificationInterface $notification, string $channel) : bool
    {
        return array_key_exists($channel, $this->contacts);
    }

    public function onNotified(NotificationInterface $notification, string $channel)
    {
        $this->notified[get_class($notification)][$channel] = true;
    }
}
