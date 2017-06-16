<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Tests\TestAsset\Message;

use Notify\Message\Sender\MessageSenderInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class TestMessageSender implements MessageSenderInterface
{
    /**
     * @var object[]
     */
    private $messages = [];

    public function send($message)
    {
        $this->messages[] = $message;
    }

    public function getMessages() : array
    {
        return $this->messages;
    }
}
