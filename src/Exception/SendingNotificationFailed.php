<?php

declare(strict_types=1);

namespace Notifier\Exception;

use Notifier\Notification\Notification;
use Notifier\Recipient\Recipient;
use ReflectionClass;
use RuntimeException;
use Throwable;

final class SendingNotificationFailed extends RuntimeException implements NotifierException
{
    /** @var string */
    private $channelName;

    /** @var Notification */
    private $notification;

    /** @var Recipient */
    private $recipient;

    public static function for(string $channelName, Notification $notification, Recipient $recipient, Throwable $error): self
    {
        $exception = new self(sprintf(
            'Failed to send %s via %s channel',
            (new ReflectionClass($notification))->getShortName(),
            $channelName
        ), 0, $error);

        $exception->channelName = $channelName;
        $exception->notification = $notification;
        $exception->recipient = $recipient;

        return $exception;
    }

    public function getChannelName(): string
    {
        return $this->channelName;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }
}
