<?php

/**
 * This file is part of the Notify package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Notify\Exception;

use RuntimeException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class UnhandledChannelException extends RuntimeException implements ExceptionInterface
{
    public static function forChannel($channel)
    {
        return new self(sprintf(
            'No message sender has been set for the "%s" channel',
            $channel
        ));
    }
}
