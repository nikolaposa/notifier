<?php

declare(strict_types=1);

namespace Notify\Tests\TestAsset\Entity;

use Notify\NotificationInterface;
use Notify\RecipientInterface;

class User implements RecipientInterface
{
    /**
     * @var array
     */
    private $contacts;

    public function __construct(array $contacts)
    {
        $this->contacts = $contacts;
    }

    public function getRecipientContact(string $channel) : string
    {
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

    public function shouldReceive(NotificationInterface $notificationDerivative, string $channel) : bool
    {
        return array_key_exists($channel, $this->contacts);
    }
}
