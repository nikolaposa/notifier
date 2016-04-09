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

use InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class InvalidContactsItemException extends InvalidArgumentException implements ExceptionInterface
{
    public static function fromInvalidItem($item)
    {
        return new self(sprintf(
            'Contact must be ContactInterface instance, %s given',
            is_object($item) ? get_class($item) : gettype($item)
        ));
    }
}
