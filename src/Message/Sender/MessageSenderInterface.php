<?php

declare(strict_types=1);

namespace Notify\Message\Sender;

use Notify\Message\Sender\Exception\RuntimeException;
use Notify\Message\Sender\Exception\UnsupportedMessageException;

interface MessageSenderInterface
{
    /**
     * @param object $message
     *
     * @throws UnsupportedMessageException
     * @throws RuntimeException
     *
     * @return void
     */
    public function send($message);
}
