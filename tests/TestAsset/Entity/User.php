<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Entity;

use Notify\NotificationDerivative;
use Notify\RecipientInterface;

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

    public function getRecipientContact(NotificationDerivative $notificationDerivative) : string
    {
        $channel = $notificationDerivative->getChannel();

        if (!array_key_exists($channel, $this->contacts)) {
            throw new \RuntimeException(sprintf(
                'User does not accept notifications through %s channel',
                $channel
            ));
        }

        return $this->contacts[$channel];
    }

    public function getRecipientName() : string
    {
        return 'John Doe';
    }

    public function shouldBeNotified(NotificationDerivative $notificationDerivative) : bool
    {
        $channel = $notificationDerivative->getChannel();

        return array_key_exists($channel, $this->contacts);
    }

    public function onNotified(NotificationDerivative $notificationDerivative)
    {
        $channel = $notificationDerivative->getChannel();
        $notification = $notificationDerivative->getNotification();

        $this->notified[get_class($notification)][$channel] = true;
    }
}
