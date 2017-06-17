<?php

declare(strict_types=1);

namespace Notify;

use Notify\Exception\UnhandledChannelException;
use Notify\Message\Sender\MessageSenderInterface;

final class Notifier implements NotifierInterface
{
    /**
     * @var array
     */
    private $messageSenders;

    public function __construct(array $messageSenders)
    {
        $this->messageSenders = $messageSenders;
    }

    public function notify(Recipients $recipients, NotificationInterface $notification)
    {
        foreach ($recipients as $recipient) {
            /* @var $recipient RecipientInterface */

            foreach ($notification->getSupportedChannels() as $channel) {
                $messageSender = $this->getMessageSender($channel);

                if (! $recipient->shouldReceive($notification, $channel)) {
                    continue;
                }

                $message = $notification->getMessage($channel, $recipient);
                $messageSender->send($message);
            }
        }
    }

    private function getMessageSender(string $channel) : MessageSenderInterface
    {
        if (! array_key_exists($channel, $this->messageSenders)) {
            throw UnhandledChannelException::forChannel($channel);
        }

        return $this->messageSenders[$channel];
    }
}
