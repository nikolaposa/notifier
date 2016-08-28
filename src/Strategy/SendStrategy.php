<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Strategy;

use Notify\NotificationInterface;
use Notify\NotificationReceiverInterface;
use Notify\Channel;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class SendStrategy implements StrategyInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Channel[]
     */
    private $channels;

    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * {@inheritdoc}
     */
    public function notify(array $notificationReceivers, NotificationInterface $notification)
    {
        return $this->notifiyIndividually($notificationReceivers, $notification);
    }

    private function notifiyIndividually(array $notificationReceivers, NotificationInterface $notification)
    {
        foreach ($notificationReceivers as $notificationReceiver) {
            /* @var $notificationReceiver NotificationReceiverInterface */

            foreach ($this->channels as $channel) {
                $channelName = $channel->getName();

                if (!$notification->isCapableFor($channelName)) {
                    continue;
                }

                if (!$notificationReceiver->shouldReceiveNotification($channelName, $notification)) {
                    continue;
                }

                $messageSender = $channel->getMessageSender();

                $message = $notification->getMessage($channelName, $notificationReceiver);

                try {
                    $messageSender->send($message);
                } catch (\Exception $ex) {
                    $this->getLogger()->error('message send failure', [
                        'notification' => $notification->getName(),
                        'message' => $message,
                        'exception' => $ex,
                    ]);

                    continue;
                }

                $this->getLogger()->info('message sent', [
                    'notification' => $notification->getName(),
                    'message' => $message,
                ]);
            }
        }
    }

    private function getLogger()
    {
        if (is_null($this->logger)) {
            $this->setLogger(new NullLogger());
        }

        return $this->logger;
    }
}
