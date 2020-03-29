<?php

declare(strict_types=1);

namespace Notifier\Channel;

use Notifier\Channel\Exception\SendingNotificationFailed;
use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;

interface Channel
{
    public function getName(): string;

    /**
     * @throws SendingNotificationFailed
     */
    public function send(Notification $notification, Recipient $recipient): void;
}
