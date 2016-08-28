<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify;

use Notify\Message\Sender\MessageSenderInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class Channel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var MessageSenderInterface
     */
    private $messageSender;

    public function __construct($name, MessageSenderInterface $messageSender)
    {
        $this->name = $name;
        $this->messageSender = $messageSender;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return MessageSenderInterface
     */
    public function getMessageSender()
    {
        return $this->messageSender;
    }
}
