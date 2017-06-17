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
        foreach ($notification->getSupportedChannels() as $channel) {
            $messageSender = $this->getMessageSender($channel);
            $notificationDerivative = new NotificationDerivative($channel, $notification);

            $recipients = $recipients->filter($notificationDerivative);

            if ($recipients->isEmpty()) {
                return;
            }

            $message = $notification->getMessage($channel, $recipients->toMessageRecipients($notificationDerivative));
            $messageSender->send($message);

            foreach ($recipients as $recipient) {
                /* @var $recipient RecipientInterface */
                $recipient->onNotified($notificationDerivative);
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
