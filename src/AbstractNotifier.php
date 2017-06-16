<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Exception\UnhandledChannelException;
use Notify\Message\Sender\MessageSenderInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
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

    /**
     * @param array $recipients
     * @param NotificationInterface $notification
     *
     * @return void
     */
    protected function notifyIndividually(array $recipients, NotificationInterface $notification)
    {
        foreach ($notification->getSupportedChannels() as $channel) {
            $messageSender = $this->getMessageSender($channel);

            $recipients = array_filter($recipients, function (RecipientInterface $recipient) use ($channel, $notification) {
                return $recipient->acceptsNotification($notification, $channel);
            });

            if (empty($recipients)) {
                return;
            }

            $messages = $notification->getMessages($channel, new Recipients($recipients));

            foreach ($messages as $message) {
                $messageSender->send($message);
            }

            foreach ($recipients as $recipient) {
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
