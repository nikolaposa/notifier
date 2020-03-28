<?php

declare(strict_types=1);

namespace Notifier\Recipient;

use Notifier\Notification\Notification;

interface Recipient
{
    public function getRecipientName(): string;

    public function getRecipientContact(string $channel, Notification $notification): ?string;
}
