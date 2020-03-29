<?php

declare(strict_types=1);

namespace Notifier\Notification;

interface Notification
{
    public function getSupportedChannels(): array;
}
