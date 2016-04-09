<?php

/*
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
class InvalidContactException extends InvalidArgumentException implements ExceptionInterface
{
    public static function fromInvalidValue($value, $type)
    {
        return new self(sprintf('%s is not a valid %s', $value, $type));
    }
}
