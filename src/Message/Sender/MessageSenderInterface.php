<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\Sender;

use Notify\Message\Sender\Exception\UnsupportedMessageException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface MessageSenderInterface
{
    /**
     * @param object $message
     *
     * @throws UnsupportedMessageException
     *
     * @return void
     */
    public function send($message);
}
