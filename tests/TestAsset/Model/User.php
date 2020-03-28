<?php

declare(strict_types=1);

namespace Notifier\Tests\TestAsset\Model;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

class User implements Recipient
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $contacts;

    public function __construct(string $name, array $contacts)
    {
        $this->name = $name;
        $this->contacts = $contacts;
    }

    public function getRecipientName(): string
    {
        return $this->name;
    }

    public function getRecipientContact(string $channel, Notification $notification): ?string
    {
        return $this->contacts[$channel] ?? null;
    }
}
