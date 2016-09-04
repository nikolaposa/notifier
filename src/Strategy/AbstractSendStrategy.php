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
use Notify\NotificationRecipientInterface;
use Notify\Exception\UnhandledChannelException;
use Notify\Message\Sender\MessageSenderInterface;
use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractSendStrategy implements StrategyInterface
{
    /**
     * @var ChannelHandler[]
     */
    private $channelHandlers;

    public function __construct(array $channelHandlers)
    {
        foreach ($channelHandlers as $channelHanlder) {
            /* @var $channelHanlder ChannelHandler */
            $this->channelHandlers[$channelHanlder->getChannel()] = $channelHanlder;
        }
    }

    /**
     * @param array $notificationReceivers
     * @param NotificationInterface $notification
     *
     * @return void
     */
    protected function notifiyIndividually(array $notificationReceivers, NotificationInterface $notification)
    {
        foreach ($notificationReceivers as $notificationReceiver) {
            /* @var $notificationReceiver NotificationRecipientInterface */

            foreach ($notification->getSupportedChannels() as $channel) {
                $channelHandler = $this->getChannelHandler($channel);

                if (!$notificationReceiver->acceptsNotification($notification, $channel)) {
                    continue;
                }

                $messageSender = $channelHandler->getMessageSender();

                $message = $notification->getMessage($channel, $notificationReceiver);

                $this->sendMessage($messageSender, $message);

                $notificationReceiver->onNotified($notification, $channel);
            }
        }
    }

    /**
     * @param string $channel
     *
     * @throws UnhandledChannelException
     *
     * @return ChannelHandler
     */
    final protected function getChannelHandler($channel)
    {
        if (!isset($this->channelHandlers[$channel])) {
            throw UnhandledChannelException::forChannel($channel);
        }

        return $this->channelHandlers[$channel];
    }

    /**
     * @param MessageSenderInterface $messageSender
     * @param MessageInterface $message
     *
     * @return void
     */
    protected function sendMessage(MessageSenderInterface $messageSender, MessageInterface $message)
    {
        $messageSender->send($message);
    }
}
