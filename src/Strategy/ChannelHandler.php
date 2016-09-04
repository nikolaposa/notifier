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

use Notify\Message\Sender\MessageSenderInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class ChannelHandler
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var MessageSenderInterface
     */
    private $messageSender;

    public function __construct($channel, MessageSenderInterface $messageSender)
    {
        $this->channel = $channel;
        $this->messageSender = $messageSender;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return MessageSenderInterface
     */
    public function getMessageSender()
    {
        return $this->messageSender;
    }
}
