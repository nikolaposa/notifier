<?php

declare(strict_types=1);

namespace Notify;

use Notify\Exception\UnhandledChannelException;
use Notify\Message\Sender\MessageSenderInterface;

abstract class AbstractNotifier implements NotifierInterface
{
    /**
     * @var array
     */
    private $messageSenders;

    public function __construct(array $messageSenders)
    {
        $this->messageSenders = $messageSenders;
    }
    
    protected function notifyIndividually(array $recipients, NotificationInterface $notification)
    {
        foreach ($notification->getSupportedChannels() as $channel) {
            $messageSender = $this->getMessageSender($channel);

            $recipients = array_filter($recipients, function (RecipientInterface $recipient) use ($channel, $notification) {
                return $recipient->acceptsNotification($notification, $channel);
            });

            if (0 === count($recipients)) {
                return;
            }

            $messages = $notification->getMessages($channel, new Recipients($recipients));

            foreach ($messages as $message) {
                $messageSender->send($message);
            }

            foreach ($recipients as $recipient) {
                /* @var $recipient RecipientInterface */
                $recipient->onNotified($notification, $channel);
            }
        }
    }

    final protected function getMessageSender(string $channel) : MessageSenderInterface
    {
        if (!isset($this->messageSenders[$channel])) {
            throw UnhandledChannelException::forChannel($channel);
        }

        return $this->messageSenders[$channel];
    }
}
