<?php

/*
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Strategy\Exception;

use RuntimeException;
use Notify\Message\MessageInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class NotHandlingMessageException extends RuntimeException implements ExceptionInterface
{
    public static function fromMessage(MessageInterface $message)
    {
        return new self(sprintf(
            'Send strategy does not handle messages of type "%s"',
            get_class($message))
        );
    }
}
