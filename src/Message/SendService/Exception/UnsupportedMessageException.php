<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Message\SendService\Exception;

use LogicException;
use Notify\Message\SendService\SendServiceInterface;
use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class UnsupportedMessageException extends LogicException implements ExceptionInterface
{
    public static function fromSendServiceAndMessage(SendServiceInterface $sendService, MessageInterface $message)
    {
        return new self(sprintf(
            '%s does not support %s messages',
            get_class($sendService),
            get_class($message)
        ));
    }
}
